class Model {
    static async select(table) {
        //fixme: await
        try {
            const response = await fetch("../ajax/model/select.php?table=" + table, {
                method: "GET",
            });

            const data = await response.json();

            if (data.status == 1) return data.data;
            else console.error("Помилка додавання ваги", data.error);
        } catch (error) {
            console.error("Помилка додавання ваги", error);
        }
    }

    static async find(table, id) {
        try {
            const response = await fetch("../ajax/model/find.php?table=" + table + "&id=" + id, {
                method: "GET",
            });

            const data = await response.json();

            if (data == 1) return data.data;
            else console.error("Помилка додавання ваги", data.error);
        } catch (error) {
            console.error("Помилка додавання ваги", error);
        }
    }

    static async store(table, body, folder = "model") {
        try {
            const response = await fetch("../ajax/" + folder + "/store.php?table=" + table, {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(body)
            });

            const data = await response.json();

            if (data.status == 1) return data.res;
            else console.error("Помилка додавання рядка в БД", data.error);
        } catch (error) {
            console.error("Помилка надсилання запиту", error);
        }
    }

    static async update(table, id, body) {
        try {
            const response = await fetch("../ajax/model/update.php?table=" + table + "&id=" + id, {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(body)
            });

            const data = await response.json();

            if (data == 1) return data.res;
            else console.error("Помилка додавання ваги", data.error);
        } catch (error) {
            console.error("Помилка додавання ваги", error);
        }
    }

    static async delete(table, id, folder = "model") {
        try {
            const response = await fetch("../ajax/" + folder + "/delete.php?table=" + table + "&id=" + id, {
                method: "DELETE",
            });

            const data = await response.json();

            if (data == 1) return data.res;
            else console.error("Помилка додавання ваги", data.error);
        } catch (error) {
            console.error("Помилка додавання ваги", error);
        }
    }
}


class User extends Model {
    //todo: add smth
    static getNorms() {
        //fixme: await
        return API.get('../ajax/user/norms.php');
    }
}
class Allergy extends Model {

    //todo: add smth

}
class Diet extends Model {

    //todo: add smth


}
class Meal extends Model {

    static getMenuDataForToday() {
        return API.get('../ajax/menu/today.php');
    }
    static ByDate(date) {

        return API.get('../ajax/meal/by_date.php?date=' + date);
    }
    //todo: add smth
    static CalcNorm(norm, value,weigth=null) {
        let coef = 0.5;
        switch (form_add_meal_data.time) {
            case 'Сніданок':
                coef = 0.25;
                break;
            case 'Ранковий перекус':
                coef = 0.075;
                break;
            case 'Обід':
                coef = 0.425;
                break;
            case 'Після обідній перекус':
                coef = 0.125;
                break;
            case 'Вечеря':
                coef = 0.175;
                break;
            default:
                coef = 1;
                break;
        }
        if(weigth!=null) value=value*weigth/100;
        return Math.round((value * 100) / (norm * coef), 1);
    }
    static getDataByProductOrRecipe(type, id) {
        const url_type = type == "r" ? 'recipe' : 'product';
        const url = '../ajax/' + url_type + '/find.php?id=' + id
        return API.get(url);
    }
    static generateMenu() {
        const url = '../ajax/menu/generate.php'
        return API.get(url);
    }
}
class Product extends Model {
    static Search(text) {
        return API.get('../ajax/product/search.php?text=' + text);
    }
    static Like(id)
    {
        console.log("product",id);
    }
}
class Recipe extends Model {
    static Search(text) {
        return API.get('../ajax/recipe/search.php?text=' + text);
    }
    static Delete(e,id){
        e.parentElement.parentElement.parentElement.remove();
        Model.delete('recipes', id).then(res => {
            console.log("delete",res);
        });
    }
    static Like(id)
    {
        console.log("recipe",id);
    }
}
class Weigth extends Model {

    static WeightsProgressForWeek() {
        return API.get('../ajax/weigth/progress.php');
    }
}
class Workout extends Model {


    static getAllToday() {
        return API.get('../ajax/workouts/all_today.php');
    }
    static ByDate(date) {

        return API.get('../ajax/workouts/by_date.php?date=' + date);

    }
}