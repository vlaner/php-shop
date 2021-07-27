const updateProduct = async () => {
    let container = document.getElementsByClassName('container')[1]

    let err = []

    try {
        document.querySelectorAll('.text-danger').forEach((e) => e.remove())
    } catch {}

    let id = parseInt(document.getElementById('prod_id').innerHTML)
    let title = document.getElementById('title').value
    let description = document.getElementById('description').value
    let price = document.getElementById('price').value

    if (!title) {
        err.push('Title is required')
    }
    if (!description) {
        err.push('Description is required')
    }
    if (!price) {
        err.push('Price is required')
    }
    if (!Number.isInteger(id)) {
        return
    }

    if (err.length > 0) {
        err.forEach((msg) => {
            let errDiv = document.createElement('div')
            errDiv.innerHTML = msg
            errDiv.classList.add('text-danger')
            container.appendChild(errDiv)
        })
        return
    }

    const formData = new FormData()
    formData.append('title', title)
    formData.append('description', description)
    formData.append('price', price)
    formData.append('id', id)

    let response = await fetch('/ajax/admin/update-product.php', {
        method: 'POST',
        body: formData,
    })

    let result = await response.json()

    if (result.errors) {
        result.errors.forEach((msg) => {
            let errDiv = document.createElement('div')
            errDiv.innerHTML = msg
            errDiv.classList.add('text-danger')
            container.appendChild(errDiv)
        })
        return
    }

    window.location.replace('/admin/prod.php')
}
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('title').value =
        document.getElementById('prod_title').innerHTML

    document.getElementById('description').value = document
        .getElementById('prod_description')
        .innerHTML.trim()

    document.getElementById('price').value =
        document.getElementById('prod_price').innerHTML

    try {
        document
            .getElementById('prod-submit')
            .addEventListener('click', updateProduct)
    } catch (error) {}
})
