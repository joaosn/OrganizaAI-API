import React from "react";
import { BrowserRouter as Router, useRoutes } from "react-router-dom";
import { QueryClientProvider } from "@tanstack/react-query";
import { AuthProvider } from "./contexts/AuthContext";
import { ThemeProvider } from "./theme/ThemeProvider";
import { ToastProvider } from "./components/Toast/ToastProvider";
import { routes } from "./routes";
import { Toaster } from "sonner";
import { LocalizationProvider } from "@mui/x-date-pickers";
import { AdapterDayjs } from "@mui/x-date-pickers/AdapterDayjs";
import { LabelPrintSettingsProvider } from "./contexts/LabelPrintSettingsContext";
import { queryClient } from "./config/query-client";

function AppRoutes() {
  const element = useRoutes(routes);
  return element;
}

function App() {
  return (
    <LocalizationProvider dateAdapter={AdapterDayjs} adapterLocale="pt-br">
    <QueryClientProvider client={queryClient}>
      <ThemeProvider>
        {(toggleTheme) => (
          <ToastProvider>
            <Toaster position="top-right" richColors />
            <button
              data-theme-toggle
              onClick={toggleTheme}
              style={{ display: "none" }}
            />
            <AuthProvider>
              <LabelPrintSettingsProvider>
                <Router>
                  <AppRoutes />
                </Router>
              </LabelPrintSettingsProvider>
            </AuthProvider>
          </ToastProvider>
        )}
      </ThemeProvider>
    </QueryClientProvider>
    </LocalizationProvider>
  );
}

export default App;
