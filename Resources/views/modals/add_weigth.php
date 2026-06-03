<div id="modalAddWeigth" class="modal">
        <div class="modal-content">
            <span onclick="closeModal('modalAddWeigth')" class="close">&times;</span>
            <form id="form_add_weight" action="/api/weights/store">
                <h3>Додавання ваги на сьогодні</h3>
                <label>Вага:</label>
                <input type="number" name="weigth" min="45" step="0.5">
                <button type="button" onclick="Ajax.Post(this)">Зберегти</button>
            </form>
        </div>
    </div>