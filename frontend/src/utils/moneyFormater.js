const moneyFormater = (value) => {
  const currency = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(
    value,
  )

  return currency
}


export default moneyFormater
