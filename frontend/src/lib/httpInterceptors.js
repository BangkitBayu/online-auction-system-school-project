import axios from "axios";

const api = axios.create({
  baseURL: 'http://localhost:8000/api/v1',
  headers: {
    "Accept" : "application/json"
  }
})


api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token')
    const isAuthEndpoint =
      config.url?.includes("/auth/register")
      || config.url.includes("/auth/login")
      || config.url?.includes("/")

    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
    else if (!isAuthEndpoint) {
      window.location.href = "/login"
    }

    return config;

  }
)

api.interceptors.response.use(
  (response) => { return response },
  (error) => {
    if (error.response?.status === 401) {
      console.log(error)
      localStorage.removeItem("token")
      localStorage.removeItem("user")
      window.location.href = "/login"
    }
    return Promise.reject(error)
  })

export default api
