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
}
