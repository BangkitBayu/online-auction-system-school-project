import api from "@/lib/httpInterceptors";

export default class CategoryAuctionService {
  async index() {
    const response = await api.get('/categories')

    return response
  }
}
