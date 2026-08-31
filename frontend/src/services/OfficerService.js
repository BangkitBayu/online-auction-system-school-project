import api from "@/lib/httpInterceptors";

export default class OfficerService {
  async index() {
    const response = await api.get('/officers')

    return response
  }

  async store(nama_petugas, username, telp, password, confirm_password) {
    const response = await api.post('/officers', {
      nama_petugas,
      username,
      telp,
      password,
      confirm_password
    })

    return response
  }

  async show(id) {
    const response = await api.get(`/officers/${id}`)

    return response
  }

  async update(payload, id) {
    const response = await api.put(`/officers/${id}`, payload)

    return response
  }

  async destroy(id) {
    const response = await api.delete(`/officers/${id}`)

    return response
  }
}
