import api from "@/lib/httpInterceptors";

export default class AuthService {
  async register(role, nama_lengkap, username, email, telp, password, confirm_password) {
    const response = await api.post("/auth/register", {
      role, nama_lengkap, username, email, telp, password, confirm_password
    })

    return response
  }

  async login(role, username, password, isRememberMe) {
    const response = await api.post("/auth/login", {
      role, username, password, isRememberMe
    })

    return response
  }

  async logout(username, role) {
    const response = await api.post("/auth/logout", {
      username, role
    })

    return response
  }
}
