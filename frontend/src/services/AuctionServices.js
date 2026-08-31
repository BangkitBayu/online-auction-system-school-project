import api from "@/lib/httpInterceptors";

export default class AuctionServices {
  async index() {
    const response = await api.get(`/auctions/live`)
    return response
  }

  async show(id_lelang) {
    const response = await api.get(`/auctions/live/${id_lelang}`)

    return response
  }
  async joinBid(id_lelang, id_barang, penawaran_harga) {
    const response = await api.post(`/auctions/${id_lelang}/join-bid`, { id_barang, penawaran_harga })

    return response
  }

  async getHistories() {
    const response = await api.get('/auctions/histories')

    return response
  }
}
