const restockProd = async (clickInfo) => {
    const productId = parseInt(clickInfo.target.attributes['product-id'].value)

    const data = JSON.stringify({ product_id: productId })

    let response = await fetch('/ajax/admin/restock.php', {
        method: 'POST',
        body: data,
    })

    let result = await response.json()
    if (result == 1) {
        let parent = clickInfo.target.parentNode.parentNode.parentNode
        let stock = parent.getElementsByClassName('stock')[0]
        let inStock = parseInt(stock.innerHTML.replace(/\D/g, '')) + 5
        stock.innerHTML = `|Available in stock ${inStock}|`
    }
    if (result == -1) {
        let restock = parent.getElementsByClassName('restock')[0]
        restock.innerHTML = 'Could not restock'
    }
}

const deleteProd = async (clickInfo) => {
    const productId = parseInt(clickInfo.target.attributes['product-id'].value)
    const data = JSON.stringify({ product_id: productId })

    let response = await fetch('/ajax/admin/delete-product.php', {
        method: 'DELETE',
        body: data,
    })

    let result = JSON.parse(await response.json())

    if (result.id == -1) {
        return
    }
    location.reload()
}

const loadItems = async () => {
    const productName = document.getElementById('product-name')
    if (productName) {
        const data = JSON.stringify({ product_name: productName.innerHTML })
        var response = await fetch('/ajax/get-products.php', {
            method: 'POST',
            body: data,
        })
    } else {
        var response = await fetch('/ajax/get-products.php', {
            method: 'POST',
        })
    }

    const result = await response.json()
    if (result === -1) {
        document.getElementById('show-more').remove()
        return
    }

    let container = document.getElementsByClassName('container')[1]
    result.forEach((element) => {
        const { price, title, id, quantity } = element

        let card = document.createElement('div')
        card.classList.add('card')

        //
        let prodLink = document.createElement('a')
        prodLink.classList.add('link-dark')
        prodLink.href = `/product/show.php?id=${id}`

        //
        let prodTitle = document.createElement('span')
        prodTitle.innerHTML = title

        prodLink.appendChild(prodTitle)

        //
        let stock = document.createElement('span')
        stock.classList.add('stock')
        stock.innerHTML = `|Available in stock ${quantity}|`

        //
        let priceDiv = document.createElement('div')
        priceDiv.innerHTML = `${price} ₽`

        //
        let restockBtn = document.createElement('a')
        restockBtn.classList.add(...['btn', 'btn-outline-dark', 'restock'])
        restockBtn.innerHTML = 'Restock (+5)'
        restockBtn.setAttribute('product-id', id)
        restockBtn.addEventListener('click', restock)

        priceDiv.appendChild(restockBtn)

        card.appendChild(prodLink)
        card.appendChild(stock)
        card.appendChild(priceDiv)

        container.appendChild(card)
    })
}

try {
    const restockBtns = document.getElementsByClassName('restock')
    for (const btn of restockBtns) {
        btn.addEventListener('click', restockProd)
    }
} catch {}

try {
    const updateBtns = document.getElementsByClassName('update')
    for (const btn of updateBtns) {
        btn.addEventListener('click', updateProd)
    }
} catch {}

try {
    const deleteBtns = document.getElementsByClassName('delete')
    for (const btn of deleteBtns) {
        btn.addEventListener('click', deleteProd)
    }
} catch {}

try {
    const showMore = document.getElementById('show-more')
    showMore.addEventListener('click', loadItems)
} catch {}
