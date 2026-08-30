export default function dateIsoFormater(iso) {
  let isoDate = new Date(iso)

  const date = String(isoDate.getDate()).padStart(2, "0")
  const month = String(isoDate.getMonth() + 1).padStart(2, "0")
  const year = String(isoDate.getFullYear())
  const hour = String(isoDate.getHours()).padStart(2, '0');
  const sec = String(isoDate.getMinutes()).padStart(2, '0');

  return `${date}-${month}-${year} ${hour}:${sec}`
}
