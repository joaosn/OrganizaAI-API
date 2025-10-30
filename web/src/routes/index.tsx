import { RouteObject } from "react-router-dom";
import { Login } from "../views/auth";
import { Dashboard } from "../views/dashboard";
import { PeopleList } from "../views/people/List";
import { PersonForm } from "../views/people/Form";
import { TaxRulesList } from "../views/tax-rules/List";
import { TaxRuleForm } from "../views/tax-rules/Form";
import { CategoryList } from "../views/categories/List";
import { CategoryForm } from "../views/categories/Form";
import { ProductList } from "../views/products/List";
import { ProductForm } from "../views/products/Form";
import { DefaultersList } from "../views/defaulters/List";
import { ServiceOrderList } from "../views/service-orders/List";
import { ServiceOrderForm } from "../views/service-orders/Form";
import { OrderList } from "../views/orders/List";
import { CashRegister } from "../views/cash-register";
import { FinancialList } from "../views/financial/List";
import { PrivateRoute } from "../components/PrivateRoute";
import { DefaulterForm } from "../views/defaulters/Form";
import { ProductListing } from "../views/public/ProductListing/index.tsx";
import { NotFound } from "../views/public/NotFound";
import { MenuList } from "../views/menus/List";
import { MenuForm } from "../views/menus/Form";
import { MenuParameterizationList } from "../views/menu-parameterization/List";
import { EstoqueList } from "../views/stock/List";
import { ListNfes } from "@/views/nfes/List";
import { PrintLabel } from "../views/labels";
import { SiteOrderOn } from "../views/orders-online";
import { SiteParameters } from "../views/site-parameters";
import { AttributeList } from "../views/attributes/List";
import { AttributeForm } from "../views/attributes/Form";
import { AttributeValueList } from "../views/attribute-values/List";
import { AttributeValueForm } from "../views/attribute-values/Form";
import PaymentLinkPage from "../views/payment/PaymentLinkPage";
import PaymentSuccessPage from "../views/payment/PaymentSuccessPage";
import { StatusBoard } from "../views/order-status/StatusBoard";
import PaymentDebitCallbackPage from "../views/payment/PaymentDebitCallbackPage";

export const routes: RouteObject[] = [
  {
    path: "/login",
    element: <Login />,
  },
  {
    path: "/",
    element: (
      <PrivateRoute>
        <Dashboard />
      </PrivateRoute>
    ),
  },
  {
    path: "/site-pedidos",
    element: (
      <PrivateRoute>
        <SiteOrderOn />
      </PrivateRoute>
    ),
  },
  {
    path: "/pedido/:store_name",
    element: <ProductListing />,
  },
  {
    path: "/404",
    element: <NotFound />,
  },
  {
    path: "/pedidos",
    element: (
      <PrivateRoute>
        <OrderList />
      </PrivateRoute>
    ),
  },
  {
    path: "/pedidos-online",
    element: (
      <PrivateRoute>
        <StatusBoard />
      </PrivateRoute>
    ),
  },
  {
    path: "/caixa",
    element: (
      <PrivateRoute>
        <CashRegister />
      </PrivateRoute>
    ),
  },
  {
    path: "/financeiro",
    element: (
      <PrivateRoute>
        <FinancialList />
      </PrivateRoute>
    ),
  },
  {
    path: "/notas-fiscais",
    element: (
      <PrivateRoute>
        <ListNfes />
      </PrivateRoute>
    ),
  },
  {
    path: "/pessoas",
    element: (
      <PrivateRoute>
        <PeopleList />
      </PrivateRoute>
    ),
  },
  {
    path: "/pessoas/novo",
    element: (
      <PrivateRoute>
        <PersonForm />
      </PrivateRoute>
    ),
  },
  {
    path: "/pessoas/:id/editar",
    element: (
      <PrivateRoute>
        <PersonForm />
      </PrivateRoute>
    ),
  },
  {
    path: "/regras-fiscais",
    element: (
      <PrivateRoute>
        <TaxRulesList />
      </PrivateRoute>
    ),
  },
  {
    path: "/regras-fiscais/novo",
    element: (
      <PrivateRoute>
        <TaxRuleForm />
      </PrivateRoute>
    ),
  },
  {
    path: "/regras-fiscais/:id/editar",
    element: (
      <PrivateRoute>
        <TaxRuleForm />
      </PrivateRoute>
    ),
  },
  {
    path: "/categorias",
    element: (
      <PrivateRoute>
        <CategoryList />
      </PrivateRoute>
    ),
  },
  {
    path: "/categorias/novo",
    element: (
      <PrivateRoute>
        <CategoryForm />
      </PrivateRoute>
    ),
  },
  {
    path: "/categorias/:id/editar",
    element: (
      <PrivateRoute>
        <CategoryForm />
      </PrivateRoute>
    ),
  },
  {
    path: "/produtos",
    element: (
      <PrivateRoute>
        <ProductList />
      </PrivateRoute>
    ),
  },
  {
    path: "/produtos/novo",
    element: (
      <PrivateRoute>
        <ProductForm />
      </PrivateRoute>
    ),
  },
  {
    path: "/produtos/:id/editar",
    element: (
      <PrivateRoute>
        <ProductForm />
      </PrivateRoute>
    ),
  },
  {
    path: "/menus",
    element: (
      <PrivateRoute>
        <MenuList />
      </PrivateRoute>
    ),
  },
  {
    path: "/menus/novo",
    element: (
      <PrivateRoute>
        <MenuForm />
      </PrivateRoute>
    ),
  },
  {
    path: "/menus/:id/editar",
    element: (
      <PrivateRoute>
        <MenuForm />
      </PrivateRoute>
    ),
  },
  {
    path: "/atributos",
    element: (
      <PrivateRoute>
        <AttributeList />
      </PrivateRoute>
    ),
  },
  {
    path: "/atributos/novo",
    element: (
      <PrivateRoute>
        <AttributeForm />
      </PrivateRoute>
    ),
  },
  {
    path: "/atributos/:id/editar",
    element: (
      <PrivateRoute>
        <AttributeForm />
      </PrivateRoute>
    ),
  },
  {
    path: "/atributo-valores",
    element: (
      <PrivateRoute>
        <AttributeValueList />
      </PrivateRoute>
    ),
  },
  {
    path: "/atributo-valores/novo",
    element: (
      <PrivateRoute>
        <AttributeValueForm />
      </PrivateRoute>
    ),
  },
  {
    path: "/atributo-valores/:id/editar",
    element: (
      <PrivateRoute>
        <AttributeValueForm />
      </PrivateRoute>
    ),
  },
  {
    path: "/parametrizacao/menu",
    element: (
      <PrivateRoute>
        <MenuParameterizationList />
      </PrivateRoute>
    ),
  },
  {
    path: "/parametrizacao/site",
    element: (
      <PrivateRoute>
        <SiteParameters />
      </PrivateRoute>
    ),
  },
  {
    path: "/inadimplentes",
    element: (
      <PrivateRoute>
        <DefaultersList />
      </PrivateRoute>
    ),
  },
  {
    path: "/inadimplentes/novo",
    element: (
      <PrivateRoute>
        <DefaulterForm
          onSuccess={() => console.log("Defaulter form submitted successfully")}
        />
      </PrivateRoute>
    ),
  },
  {
    path: "/ordens-servico",
    element: (
      <PrivateRoute>
        <ServiceOrderList />
      </PrivateRoute>
    ),
  },
  {
    path: "/ordens-servico/novo",
    element: (
      <PrivateRoute>
        <ServiceOrderForm />
      </PrivateRoute>
    ),
  },
  {
    path: "/ordens-servico/:id/editar",
    element: (
      <PrivateRoute>
        <ServiceOrderForm />
      </PrivateRoute>
    ),
  },
  {
    path: "/estoque",
    element: (
      <PrivateRoute>
        <EstoqueList />
      </PrivateRoute>
    ),
  },
  {
    path: "/etiquetas/imprimir",
    element: (
      <PrivateRoute>
        <PrintLabel />
      </PrivateRoute>
    ),
  },
  // Rotas públicas de pagamento
  {
    path: "/pagar/:slug",
    element: <PaymentLinkPage />,
  },
  {
    path: "/payment/success/:storeSlug?",
    element: <PaymentSuccessPage />,
  },
  {
    path: "/payment/debit/callback",
    element: <PaymentDebitCallbackPage />,
  },
  {
    path: "/3ds/:status/:slug",
    element: <PaymentDebitCallbackPage />,
  },
];
