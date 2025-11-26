(function () {
    const listEl = document.getElementById('ratings-list-js');

    // escape HTML để tránh XSS khi render dữ liệu từ server
    function escapeHtml(str) {
        if (str === null || str === undefined) return '';
        return String(str).replace(/[&<>"'`]/g, function (s) {
            return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', '\'': '&#39;', '`': '&#96;' })[s];
        });
    }

    // Lấy dữ liệu ratings từ endpoint JSON: /admin/ratings/data?page=...
    async function fetchRatings(page = 1) {
        const rating = document.getElementById("filter-rating").value;
        const response = document.getElementById("filter-response").value;
        const dateFrom = document.getElementById("filter-date-from").value;
        const dateTo   = document.getElementById("filter-date-to").value;

        const params = new URLSearchParams({
            page,
            rating,
            response,
            date_from: dateFrom,
            date_to: dateTo
        });
        const url = `/admin/ratings/data?page=${page}&${params.toString()}`;
        const res = await fetch(url, {
            headers: { 'Accept': 'application/json' },
            credentials: 'same-origin'
        });

        const contentType = res.headers.get('content-type') || '';
        if (!res.ok) {
            let msg = `Lỗi khi tải dữ liệu (HTTP ${res.status})`;
            if (contentType.includes('application/json')) {
                const body = await res.json().catch(() => ({}));
                msg = body.message || msg;
            }
            throw new Error(msg);
        }

        if (!contentType.includes('application/json')) {
            throw new Error('Không nhận được JSON từ server. Có thể bị redirect (chưa đăng nhập).');
        }

        return res.json();
    }

    // Render bảng từ JSON
    async function render(page = 1) {
        if (!listEl) return;
        try {
            listEl.innerHTML = '<tr><td colspan="7">Đang tải...</td></tr>';
            const res = await fetchRatings(page);
            const rows = (Array.isArray(res) ? res : res.data || []).map(r => {
                const user = r.user ? escapeHtml(r.user.name +"("+ r.user.id+")") : 'Người dùng';
                const product = r.product ? escapeHtml(r.product.name +"("+ r.product.id+")") : '---';
                const comment = r.comment ? escapeHtml(r.comment) : 'Không có bình luận';
                const response = r.response ? escapeHtml(r.response) : 'Chưa phản hồi';
                return `
                    <tr class="rating-row"
                        data-id="${r.id}"
                        data-user="${user}"
                        data-product="${product}"
                        data-rating="${r.rating}"
                        data-comment="${comment}"
                        data-time="${escapeHtml(r.created_at)}"
                        data-response="${response}"
                    >
                        <td>${r.id}</td>
                        <td>${user}</td>
                        <td style="max-width:280px">${product}</td>
                        <td>${r.rating} / 5 </td>
                        <td style="max-width:300px">${comment}</td>
                        <td style="max-width:280px">${response}</td>
                        <td>${escapeHtml(r.created_at)}</td>
                        <td><button class="btn-delete" data-id="${r.id}">Xóa</button></td>
                    </tr>
                `;
            }).join('\n');

            listEl.innerHTML = rows || '<tr><td colspan="7">Không có đánh giá.</td></tr>';
            attachDeletes();

        } catch (e) {
            console.error(e);
            listEl.innerHTML = `<tr><td colspan="7">Lỗi: ${escapeHtml(e.message)}</td></tr>`;
        }

        attachRowClicks();
    }

    // Gắn sự kiện click từng hàng
    function attachRowClicks() {
        document.querySelectorAll('.rating-row').forEach(row => {
            row.addEventListener('click', () => {
                document.getElementById('detail-id').value = row.dataset.id;
                document.getElementById('detail-user').value = row.dataset.user;
                document.getElementById('detail-product').value = row.dataset.product;
                document.getElementById('detail-rating').value = row.dataset.rating;
                document.getElementById('detail-comment').value = row.dataset.comment;
                document.getElementById('detail-time').value = row.dataset.time;
                if(row.dataset.response === 'Chưa phản hồi') {
                    document.getElementById('detail-response').value = '';
                } else {
                    document.getElementById('detail-response').value = row.dataset.response;
                }
                console.log('Clicked row for rating ID ' + row.dataset);s
            });
        });
    }

    // Gắn sự kiện cho nút Xóa
    function attachDeletes() {
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.removeEventListener('click', btn._ratingDeleteHandler); // tránh gắn 2 lần
            const handler = async function () {
                const id = btn.dataset.id;
                if (!id) return;
                if (!confirm('Bạn có chắc muốn xóa đánh giá này?')) return;
                try {
                    const tokenEl = document.querySelector('meta[name=csrf-token]');
                    const token = tokenEl ? tokenEl.getAttribute('content') : '';
                    const res = await fetch(`/admin/ratings/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token || '',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin'
                    });
                    const ct = res.headers.get('content-type') || '';
                    const bodyText = await res.text();
                    console.log('DELETE /admin/ratings/' + id, res.status, ct, bodyText);
                    let body = {};
                    try { body = ct.includes('application/json') ? JSON.parse(bodyText) : { text: bodyText }; } catch (e) { body = { text: bodyText }; }
                    if (res.ok) {
                        alert(body.message || 'Đã xóa đánh giá.');
                        render();
                    } else {
                        alert(body.message || `Xóa thất bại (HTTP ${res.status})`);
                    }
                } catch (err) {
                    console.error(err);
                    alert('Lỗi khi gửi yêu cầu xóa: ' + (err.message || err));
                }
            };
            btn.addEventListener('click', handler);
            btn._ratingDeleteHandler = handler;
        });
    }

    // Gửi phản hồi
    document.getElementById('save-response').addEventListener('click', async () => {
        const id = document.getElementById('detail-id').value;
        const response = document.getElementById('detail-response').value;
        const token = document.querySelector('meta[name=csrf-token]').getAttribute('content');
        const res = await fetch(`/admin/ratings/${id}/response`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({ response })
        });``

        const data = await res.json();
        alert(data.message);
        render()
    });

    // Nút reset filter
    document.getElementById("reset-filter").addEventListener("click", () => {
        document.getElementById("filter-rating").value = "";
        document.getElementById("filter-response").value = "";
        document.getElementById("filter-date-from").value = "";
        document.getElementById("filter-date-to").value = "";

        render();
    });


    // Tự động load khi DOM sẵn sàng
    document.addEventListener('DOMContentLoaded', function () {
        render();
    });

    document.getElementById('apply-filter').addEventListener('click', () => {
        render(1);
    });

})();
