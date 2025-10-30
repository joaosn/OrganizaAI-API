import { motion } from "framer-motion";
import { Store } from "lucide-react";

export function NotFound() {
  return (
    <div className="min-h-screen flex flex-col items-center justify-center px-4 text-center bg-gray-100">
      <motion.div
        initial={{ scale: 0.8, opacity: 0 }}
        animate={{ scale: 1, opacity: 1 }}
        transition={{ duration: 0.5 }}
      >
        <Store className="w-20 h-20 text-primary mb-4" />
      </motion.div>
      <motion.h1
        initial={{ y: -20, opacity: 0 }}
        animate={{ y: 0, opacity: 1 }}
        transition={{ duration: 0.5, delay: 0.2 }}
        className="text-3xl font-semibold mb-2 text-gray-800"
      >
        Loja não encontrada
      </motion.h1>
      <motion.p
        initial={{ y: 20, opacity: 0 }}
        animate={{ y: 0, opacity: 1 }}
        transition={{ duration: 0.5, delay: 0.4 }}
        className="text-muted-foreground mb-6 max-w-md text-gray-600"
      >
        Desculpe, mas não conseguimos encontrar a loja que você está procurando.
      </motion.p>
    </div>
  );
}
