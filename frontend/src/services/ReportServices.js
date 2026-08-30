import api from "@/lib/httpInterceptors";

export default class ReportServices {
  async index(params = {}) {
    const response = await api.get(`/reports`, { params })

    return response
  }

  async printAuctionReportDetail(id) {
    const response = await api.get(`/reports/print/${id}`, { responseType: 'blob' })

    return response
  }
}
