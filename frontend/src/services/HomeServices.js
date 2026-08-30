import api from "@/lib/httpInterceptors";

export default class HomeServices {
  async index() {
    const response = await api.get('/assets')

    return response
  }

  async show(id) {
    const response = await api.get(`/assets/${id}`)

    return response
  }
}
