import client from "@/services/api";
import type { SignInData, SignUpData } from "@/types";

export default {
  signin: (data: SignInData) => {
    return client.post("/api/login", data);
  },

  signup: (data: SignUpData) => {
    return client.post("/api/register", data);
  },

  logout: () => {
    return client.post("/api/logout");
  },
};
