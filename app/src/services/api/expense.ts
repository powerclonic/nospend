import client from "@/services/api";
import { Expense } from "@/types";

export default {
  create: (data: Expense) => {
    return client.post("/api/expenses", data);
  },
  update: (data: Expense) => {
    return client.post(`/api/expenses/${data.id}`, { ...data, _method: "PUT" });
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
};
