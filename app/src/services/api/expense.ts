import client from "@/services/api";
import { Expense, ExpenseInput } from "@/types";

export default {
  create: (data: ExpenseInput) => {
    return client.post("/api/expenses", data);
  },
  update: (data: ExpenseInput, id: number) => {
    return client.post(`/api/expenses/${id}`, { ...data, _method: "PUT" });
  },
  delete: (data: Expense) => {
    return client.post(`/api/expenses/${data.id}`, { _method: "DELETE" });
  },
  pay: (data: Expense) => {
    return client.post(`/api/expenses/${data.id}`, {
      status: "PAID",
      _method: "PUT",
    });
  },
  hints: () => {
    return client.get("/api/expenses/details");
  }
};
