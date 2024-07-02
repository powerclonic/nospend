import client from "@/services/api";

export default {
  dashboard: () => {
    return client.get("/api/expenses");
  },
};
