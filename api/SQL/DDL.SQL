-- =====================================================================
-- database
-- =====================================================================
create database if not exists organizaai
  default character set utf8mb4
  collate utf8mb4_0900_ai_ci;

use organizaai;

-- =====================================================================
-- helpers: domínio pequeno como check (sem tabelas-lookup desnecessárias)
-- =====================================================================

-- valores aceitos em varios lugares: 'pf' e 'pj', 'mensal' etc. via CHECK

-- =====================================================================
-- clientes
-- =====================================================================
create table clientes (
  idcliente           bigint unsigned auto_increment
, tipo_pessoa         varchar(2) not null
, nome                varchar(160) not null              -- pf: nome completo; pj: razao social
, nome_fantasia       varchar(160) null                  -- pj opcional
, cpf_cnpj            varchar(20) not null               -- apenas dígitos ou com máscara, você decide na app
, ie_rg               varchar(30)  null                  -- inscrição estadual ou RG
, im                  varchar(30)  null                  -- inscrição municipal
, email               varchar(160) null
, telefone            varchar(30)  null
, data_cadastro       datetime not null default current_timestamp
, ativo               tinyint(1) not null default 1

, constraint pk_clientes primary key (idcliente)
, constraint uq_clientes_cpf_cnpj unique (cpf_cnpj)
, constraint ck_clientes_tipo check (tipo_pessoa in ('pf','pj'))
) engine=innodb comment='cadastro de clientes (pf/pj)';

create index ix_clientes_nome on clientes (nome);
create index ix_clientes_ativo on clientes (ativo);

-- =====================================================================
-- enderecos do cliente (n:1)
-- =====================================================================
create table clientes_enderecos (
  idendereco          bigint unsigned auto_increment
, idcliente           bigint unsigned not null
, tipo                varchar(20) not null               -- 'principal','cobranca','instalacao' (livre)
, logradouro          varchar(180) not null
, numero              varchar(20)  null
, complemento         varchar(100) null
, bairro              varchar(100) null
, cidade              varchar(120) not null
, uf                  char(2) not null
, cep                 varchar(15)  null
, pais                varchar(60)  not null default 'BR'
, principal           tinyint(1) not null default 0

, constraint pk_clientes_enderecos primary key (idendereco)
, constraint fk_enderecos_clientes
    foreign key (idcliente) references clientes (idcliente)
    on update cascade on delete cascade
) engine=innodb comment='endereços do cliente';

create index ix_enderecos_cliente on clientes_enderecos (idcliente, principal desc);

-- =====================================================================
-- contatos do cliente (n:1)
-- =====================================================================
create table clientes_contatos (
  idcontato           bigint unsigned auto_increment
, idcliente           bigint unsigned not null
, nome                varchar(120) not null
, email               varchar(160) null
, telefone            varchar(30)  null
, cargo               varchar(80)  null
, principal           tinyint(1) not null default 0

, constraint pk_clientes_contatos primary key (idcontato)
, constraint fk_contatos_clientes
    foreign key (idcliente) references clientes (idcliente)
    on update cascade on delete cascade
) engine=innodb comment='contatos do cliente';

create index ix_contatos_cliente on clientes_contatos (idcliente, principal desc);

-- =====================================================================
-- sistemas ofertados (catálogo)
-- =====================================================================
create table sistemas (
  idsistema           bigint unsigned auto_increment
, nome                varchar(120) not null
, categoria           varchar(60)  null                  -- ex: 'erp','delivery','site'
, descricao           text         null
, ativo               tinyint(1)   not null default 1

, constraint pk_sistemas primary key (idsistema)
, constraint uq_sistemas_nome unique (nome)
) engine=innodb comment='catálogo de sistemas/produtos de software';

create index ix_sistemas_ativo on sistemas (ativo);

-- =====================================================================
-- planos do sistema (preço base + impostos padrão)
-- =====================================================================
create table sistemas_planos (
  idplano             bigint unsigned auto_increment
, idsistema           bigint unsigned not null
, nome                varchar(120) not null              -- ex: 'starter','pro','enterprise'
, descricao           text null
, ciclo_cobranca      varchar(12) not null               -- 'mensal','trimestral','semestral','anual'
, preco_base_sem_imposto decimal(12,2) not null default 0.00
, aliquota_imposto_percent decimal(5,2) not null default 0.00  -- ex: 5.00 = 5%
, preco_base_com_imposto decimal(12,2) as
    (round(preco_base_sem_imposto * (1 + (aliquota_imposto_percent/100)), 2)) stored
, ativo               tinyint(1) not null default 1

, constraint pk_sistemas_planos primary key (idplano)
, constraint fk_planos_sistemas
    foreign key (idsistema) references sistemas (idsistema)
    on update cascade on delete restrict
, constraint ck_planos_ciclo check (ciclo_cobranca in ('mensal','trimestral','semestral','anual'))
) engine=innodb comment='planos de cada sistema (preço base e alíquota padrão)';

create index ix_planos_sistema on sistemas_planos (idsistema, ativo);

-- =====================================================================
-- add-ons opcionais (ex: módulo fiscal, usuários extras)
-- =====================================================================
create table sistemas_addons (
  idaddon             bigint unsigned auto_increment
, idsistema           bigint unsigned not null
, nome                varchar(120) not null              -- ex: 'modulo_fiscal','usuarios_extras'
, descricao           text null
, preco_sem_imposto   decimal(12,2) not null default 0.00
, aliquota_imposto_percent decimal(5,2) not null default 0.00
, preco_com_imposto   decimal(12,2) as
    (round(preco_sem_imposto * (1 + (aliquota_imposto_percent/100)), 2)) stored
, ativo               tinyint(1) not null default 1

, constraint pk_sistemas_addons primary key (idaddon)
, constraint fk_addons_sistemas
    foreign key (idsistema) references sistemas (idsistema)
    on update cascade on delete restrict
) engine=innodb comment='opcionais cobrados à parte';

create index ix_addons_sistema on sistemas_addons (idsistema, ativo);

-- =====================================================================
-- assinaturas do cliente
--  - pode usar um plano como base OU preço customizado (override)
--  - preco_* na assinatura permitem travar valor negociado por cliente
-- =====================================================================
create table assinaturas (
  idassinatura        bigint unsigned auto_increment
, idcliente           bigint unsigned not null
, idsistema           bigint unsigned not null
, idplano             bigint unsigned null                -- pode ser null em caso 100% custom
, ciclo_cobranca      varchar(12) not null                -- redundância útil p/ travar contrato
, dia_vencimento      tinyint unsigned not null default 5 -- 1..28
, data_inicio         date not null
, data_fim            date null
, status              varchar(12) not null default 'ativa' -- 'ativa','suspensa','cancelada','trial'

-- valores negociados (override). se null, herda do plano
, preco_sem_imposto   decimal(12,2) null
, aliquota_imposto_percent decimal(5,2) null
, preco_com_imposto   decimal(12,2) as (
    round(
      coalesce(preco_sem_imposto,    (select p.preco_base_sem_imposto from sistemas_planos p where p.idplano = idplano))
      * (1 + (coalesce(aliquota_imposto_percent, (select p.aliquota_imposto_percent from sistemas_planos p where p.idplano = idplano))/100))
    , 2)
  ) stored

, observacoes         varchar(400) null
, criado_em           datetime not null default current_timestamp
, atualizado_em       datetime not null default current_timestamp on update current_timestamp

, constraint pk_assinaturas primary key (idassinatura)
, constraint fk_assinaturas_clientes
    foreign key (idcliente) references clientes (idcliente)
    on update cascade on delete restrict
, constraint fk_assinaturas_sistemas
    foreign key (idsistema) references sistemas (idsistema)
    on update cascade on delete restrict
, constraint fk_assinaturas_plano
    foreign key (idplano) references sistemas_planos (idplano)
    on update cascade on delete set null
, constraint ck_assinaturas_status check (status in ('ativa','suspensa','cancelada','trial'))
, constraint ck_assinaturas_ciclo  check (ciclo_cobranca in ('mensal','trimestral','semestral','anual'))
, constraint ck_assinaturas_dia     check (dia_vencimento between 1 and 28)
) engine=innodb comment='contratos de assinatura por cliente';

create index ix_assinaturas_cliente on assinaturas (idcliente, status);
create index ix_assinaturas_sistema on assinaturas (idsistema, status);

-- =====================================================================
-- add-ons contratados por assinatura
-- =====================================================================
create table assinaturas_addons (
  idassinatura_addon  bigint unsigned auto_increment
, idassinatura        bigint unsigned not null
, idaddon             bigint unsigned not null
, quantidade          int unsigned not null default 1
, preco_sem_imposto   decimal(12,2) null                 -- override; se null, usa do addon
, aliquota_imposto_percent decimal(5,2) null
, preco_com_imposto   decimal(12,2) as (
    round(
      coalesce(preco_sem_imposto,    (select a.preco_sem_imposto from sistemas_addons a where a.idaddon = idaddon))
      * (1 + (coalesce(aliquota_imposto_percent,(select a.aliquota_imposto_percent from sistemas_addons a where a.idaddon = idaddon))/100))
    , 2)
  ) stored
, ativo               tinyint(1) not null default 1

, constraint pk_assinaturas_addons primary key (idassinatura_addon)
, constraint fk_assinaturas_addons_assinatura
    foreign key (idassinatura) references assinaturas (idassinatura)
    on update cascade on delete cascade
, constraint fk_assinaturas_addons_addon
    foreign key (idaddon) references sistemas_addons (idaddon)
    on update cascade on delete restrict
) engine=innodb comment='itens adicionais contratados em cada assinatura';

create unique index uq_assinaturas_addon_unique
  on assinaturas_addons (idassinatura, idaddon);

-- =====================================================================
-- historico de precificacao (auditoria leve)
-- =====================================================================
create table precos_historico (
  idpreco_hist        bigint unsigned auto_increment
, referencia_tipo     varchar(12) not null               -- 'plano','assinatura','addon','assinatura_addon'
, referencia_id       bigint unsigned not null
, campo               varchar(40)  not null              -- 'preco_sem_imposto','aliquota_imposto_percent'
, valor_antigo        decimal(12,2) null
, valor_novo          decimal(12,2) null
, alterado_em         datetime not null default current_timestamp
, alterado_por        varchar(80)  null                  -- user/app que alterou

, constraint pk_precos_historico primary key (idpreco_hist)
, constraint ck_hist_referencia check (referencia_tipo in ('plano','assinatura','addon','assinatura_addon'))
) engine=innodb comment='auditoria simples de mudanças de preço/aliquota';

create index ix_hist_ref on precos_historico (referencia_tipo, referencia_id, alterado_em desc);

-- =====================================================================
-- visao util: valores atuais da assinatura (com e sem imposto)
-- =====================================================================
create or replace view v_assinaturas_resumo as
select
  a.idassinatura
, a.idcliente
, a.idsistema
, a.idplano
, a.status
, a.ciclo_cobranca
, a.dia_vencimento
, a.data_inicio
, a.data_fim
, coalesce(a.preco_sem_imposto, p.preco_base_sem_imposto)      as preco_sem_imposto
, coalesce(a.aliquota_imposto_percent, p.aliquota_imposto_percent) as aliquota_imposto_percent
, a.preco_com_imposto                                           as preco_com_imposto
from assinaturas a
left join sistemas_planos p on p.idplano = a.idplano;

-- opcional: somar add-ons
create or replace view v_assinaturas_total_mensal as
select
  a.idassinatura
, a.idcliente
, a.idsistema
, a.status
, a.ciclo_cobranca
, round(
    (select coalesce(v.preco_com_imposto,0)
       from v_assinaturas_resumo v
      where v.idassinatura = a.idassinatura)
    +
    coalesce((
      select sum(aa.preco_com_imposto * aa.quantidade)
        from assinaturas_addons aa
       where aa.idassinatura = a.idassinatura
         and aa.ativo = 1
    ),0)
  ,2) as total_com_imposto
from assinaturas a;
