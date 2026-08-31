import api from "@/lib/httpInterceptors";

export default class ItemServices {
  async index() {
    const response = await api.get(`/auctions`)

    return response
  }

  async show(id) {
    const response = await api.get(`/auctions/${id}`)
    return response
  }

  async store(payload) {
    const response = await api.post('/auctions', payload)

    return response
  }

  async edit(id, payload) {
    const response = await api.post(`/auctions/${id}`, payload)

    return response
  }

  async getItemById(id) {
    const response = await api.get(`/auctions/${id}`)

    return response
  }

  async destroy(id) {
    const response = await api.delete(`/auctions/${id}`)
    return response
  }
}
