import client from "@/services/api";

export default {
  get: async () => {
    return (await client.get("/sanctum/csrf-cookie")).data;
  },
};
