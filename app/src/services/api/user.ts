import client from "@/services/api";

export default {
  dashboard: () => {
    return client.get("/api/expenses");
  },
  expenses: (month: number, year: number) => {
    return client.get("/api/expenses", { params: { month, year } });
  },
};
