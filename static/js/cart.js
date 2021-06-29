const removeItem = async (clickInfo) => {
    const productId = parseInt(
        clickInfo.explicitOriginalTarget.attributes['product-id'].value
    )

    const data = JSON.stringify({ product_id: productId })

    let response = await fetch('/ajax/rm-from-cart.php', {
        method: 'POST',
        body: data,
    })

    location.reload()
}

const handleAmount = async (clickInfo) => {
    const productId = parseInt(
        clickInfo.explicitOriginalTarget.parentNode.attributes['product-id']
            .value
    )
    const action = clickInfo.explicitOriginalTarget.classList[1]

    if (!(action == 'decrease' || action == 'increase')) return

    const data = JSON.stringify({ product_id: productId, action: action })

    let response = await fetch('/ajax/handle-prod-amount.php', {
        method: 'POST',
        body: data,
    })

    let result = await response.json()

    if (!(result === -1)) {
        let parent = clickInfo.explicitOriginalTarget.parentNode
        let summary = document.getElementById('summary')
        let overallItems = document.getElementById('items-count')
        let amount = parent.getElementsByClassName('amount')[0]
        let stock = parent.getElementsByClassName('stock')[0]

        summary.innerHTML = `Summary ${result.summary} ₽`
        overallItems.innerHTML = `Overall items ${result.overallItems}`
        amount.innerHTML = `Amount ${result.itemCount}`
        stock.innerHTML = `|Available in stock ${result.inStock}|`
    }
}

const buyProducts = async () => {
    let response = await fetch('/ajax/purchase.php', {
        method: 'POST',
    })

    let result = await response.json()
    const buyBtn = document.getElementById('buy')

    if (result == -2) buyBtn.innerHTML = 'Not enough money'
    if (result == -1) {
        location.reload()
    }
    if (result == 1) {
        document.getElementsByClassName('container-flex')[0].innerHTML =
            'Successfully bought items'
    }
}

const restock = async (clickInfo) => {
    console.log(clickInfo)
    const productId = parseInt(
        clickInfo.explicitOriginalTarget.attributes['product-id'].value
    )

    const data = JSON.stringify({ product_id: productId })

    let response = await fetch('/ajax/restock.php', {
        method: 'POST',
        body: data,
    })

    let result = JSON.parse(await response.json())

    if (result == 1) location.reload()
    if (result == -1) {
        let restock = parent.getElementsByClassName('restock')[0]
        restock.innerHTML = 'Could not restock'
    }
}

const delBtns = document.getElementsByClassName('delete')
const restockBtns = document.getElementsByClassName('restock')
const cartBtns = document.getElementsByClassName('cart-btn')

try {
    document.getElementById('buy').addEventListener('click', buyProducts)
} catch {}

for (const btn of cartBtns) {
    btn.addEventListener('click', handleAmount)
}
for (const btn of restockBtns) {
    btn.addEventListener('click', restock)
}

for (const btn of delBtns) {
    btn.addEventListener('click', removeItem)
}
