import client from "@/services/api";
import type { SignInData, SignUpData } from "@/types";

export default {
  signIn: (data: SignInData) => {
    return client.post("/auth/login", data);
  },

  signup: (data: SignUpData) => {
    return client.post("/auth/register", data);
  },

  logout: () => {
    return client.post("/auth/logout");
  },

  check: () => {
    return client.get("/auth/auth-check");
  }
};
