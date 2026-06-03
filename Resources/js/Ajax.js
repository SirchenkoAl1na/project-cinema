class Ajax{
    static Post(form_button) {
        API.post(form.action,this.getFataFromForm(form_button.parentElement.id));
        closeModal(form.parentElement.id);
    }
    static getFataFromForm(form_id){
        return Array.from([...document.querySelectorAll(`#${form_id} input`), ...document.querySelectorAll(`#${form_id} select`), ...document.querySelectorAll(`#${form_id} textarea`)]).reduce((acc, field) => ({...acc,[field.name]: field.value}),{});
    }
}