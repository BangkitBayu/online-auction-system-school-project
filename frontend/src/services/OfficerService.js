import api from "@/lib/httpInterceptors";

export default class OfficerService {
  async index() {
    const response = await api.get('/officers')

    return response
  }
}
