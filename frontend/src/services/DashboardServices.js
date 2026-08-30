import api from "@/lib/httpInterceptors";

export default class DashboardServices {
  async index() {
    const response = await api.get('/dashboard')

    return response
  }
}
