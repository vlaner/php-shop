const removeItem = async (clickInfo) => {
    const productId = parseInt(clickInfo.target.attributes['product-id'].value)

    const data = JSON.stringify({ product_id: productId })

    let response = await fetch('/ajax/rm-from-cart.php', {
        method: 'POST',
        body: data,
    })

    location.reload()
}

const handleAmount = async (clickInfo) => {
    const productId = parseInt(
        clickInfo.target.parentNode.attributes['product-id'].value
    )
    const action = clickInfo.target.classList[1]

    if (!(action == 'decrease' || action == 'increase')) return

    const data = JSON.stringify({ product_id: productId, action: action })

    let response = await fetch('/ajax/handle-prod-amount.php', {
        method: 'POST',
        body: data,
    })

    let result = await response.json()

    let parent = clickInfo.target.parentNode

    let summary = document.getElementById('summary')
    let overallItems = document.getElementById('items-count')
    let amount = parent.getElementsByClassName('amount')[0]
    let stock = parent.getElementsByClassName('stock')[0]

    summary.innerHTML = `Summary ${result.summary} ₽`
    overallItems.innerHTML = `Overall items ${result.overallItems}`
    amount.innerHTML = `Amount ${result.itemCount}`
    stock.innerHTML = `|Available in stock ${result.inStock}|`
}

const buyProducts = async (clickInfo) => {
    let response = await fetch('/ajax/purchase.php', {
        method: 'POST',
    })

    let result = await response.json()
    const buyBtn = document.getElementById('buy')

    if (result == -2) buyBtn.innerHTML = 'Not enough money'
    if (result == -1) {
        let container = document.getElementsByClassName('container')[1]
        let errDiv = document.createElement('div')
        errDiv.classList.add('text-danger')
        errDiv.innerHTML = 'Check products stock or amount'

        container.prepend(errDiv)
    }
    if (result == 1) {
        document.getElementsByClassName('container')[1].innerHTML =
            'Successfully bought items'
    }
}

const delBtns = document.getElementsByClassName('delete')
const cartBtns = document.getElementsByClassName('cart-btn')

try {
    document.getElementById('buy').addEventListener('click', buyProducts)
} catch {}

for (const btn of cartBtns) {
    btn.addEventListener('click', handleAmount)
}

for (const btn of delBtns) {
    btn.addEventListener('click', removeItem)
}
