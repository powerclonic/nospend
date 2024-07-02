import client from "@/services/api";

export default {
  signin: (email: string, password: string) => {
    return client.post("/login", { email, password });
  },
};
