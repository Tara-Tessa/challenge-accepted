{

    const handleChangeSort = e => {
        submitWithJSSort();
    }


    const handleInputFilter = e => {
        submitWithJSFilter();
    }


    const handleInputSearch = e => {
        submitWithJS();
    }

    const submitWithJSSort = async () => {
        // data van het formulier ophalen
        // check de console voor de resultaten
        const $form = document.querySelector('.filter-form');
        const data = new FormData($form);
        const entries = [...data.entries()];
        console.log('entries:', entries);
        const qs = new URLSearchParams(entries).toString();
        console.log('querystring', qs);
        const url = `${$form.getAttribute('action')}?${qs}`;
        console.log('url', url);

        // request naar de server
        const response = await fetch(url, {
            headers: new Headers({
                Accept: 'application/json'
            })
        });
        console.log(response);
        // opslaan van de tips die de server heeft terugegeven
        const tips = await response.json();
        console.log(tips);
        updateList(tips);

        // aanpassen van de url en beschikbaar maken van de back button
        window.history.pushState(
            {},
            '',
            `${window.location.href.split('?')[0]}?${qs}`
        );
    }

    const submitWithJSFilter = async () => {
        // data van het formulier ophalen
        // check de console voor de resultaten
        const $form = document.querySelector('.box-sort');
        const data = new FormData($form);
        const entries = [...data.entries()];
        console.log('entries:', entries);
        const qs = new URLSearchParams(entries).toString();
        console.log('querystring', qs);
        const url = `${$form.getAttribute('action')}?${qs}`;
        console.log('url', url);

        // request naar de server
        const response = await fetch(url, {
            headers: new Headers({
                Accept: 'application/json'
            })
        });
        console.log(response);
        // opslaan van de tips die de server heeft terugegeven
        const tips = await response.json();
        console.log(tips);
        updateList(tips);

        // aanpassen van de url en beschikbaar maken van de back button
        window.history.pushState(
            {},
            '',
            `${window.location.href.split('?')[0]}?${qs}`
        );
    }

    const submitWithJS = async () => {
        const $form = document.querySelector('.search_form');
        const data = new FormData($form);
        const entries = [...data.entries()];
        console.log('entries:', entries);
        const qs = new URLSearchParams(entries).toString();
        console.log('querystring', qs);
        const url = `index.php?${qs}`;
        console.log('url', url);

        window.history.pushState(
            {},
            '',
            `${window.location.href.split('?')[0]}?${qs}`
        );

        const response = await fetch(url, {
            headers: new Headers({
                Accept: 'application/json'
            })
        });
        const tips = await response.json();
        updateList(tips);
    };

    const updateList = tips => {
        const $list = document.querySelector('.list');
        $list.innerHTML = tips.map($tip => {
            return `<li class="box-tip">
                <a class="unset-link" href="index.php?page=detail&id=${$tip['id']}">
                    <img class="crop" src="assets/uploads/${$tip['afbeelding']}" alt="${$tip['titel']}">
                    <h2 class="text text-search"><${$tip['titel']}</h2>
                </a>
                <div class="box-left">
                    <p class="text text-light">${$tip['auteur']}</p>
                    <form class="smallFavoritesForm" action="index.php?page=tips" method="post">
                        <input type="hidden" name="action" value="addToFavorites">
                        <input type="hidden" name="tip_id" value="${$tip['id']}">
                        <input class="hidden favorite" type="checkbox" id="favorite" name="favorite">
                        <label class="heart" for="favorite"></label>
                        <input class="submit" type="submit" value="Toevoegen">
                    </form>
                </div>
            </li>`
        }).join('');
    }

    const handleSmallFavoritesInput = e => {
        e.preventDefault();
        const $form = e.currentTarget;
        postSmallFavorite($form.getAttribute('action'), formdataToJson($form));
    }

    const postSmallFavorite = async (url, data) => {
        const response = await fetch(url, {
            method: "POST",
            headers: new Headers({
                'Content-Type': 'application/json'
            }),
            body: JSON.stringify(data)
        });
        console.log(response);
        const returned = await response.json();
        console.log(returned);
        if (returned.error) {
            console.log(returned.error);
        } else {
            showSmallFavorite(returned);
        }
    }

    const showSmallFavorite = $tip => {
        const $checkbox = document.querySelector('.favorite');
        if ($tip['favorite'] == 1) {
            $checkbox.innerHTML += `checked`
            console.log('checked');
        }
    }

    const handleFavoritesInput = e => {
        e.preventDefault();
        const $form = e.currentTarget;
        postFavorite($form.getAttribute('action'), formdataToJson($form));
    }

    const postFavorite = async (url, data) => {
        const response = await fetch(url, {
            method: "POST",
            headers: new Headers({
                'Content-Type': 'application/json'
            }),
            body: JSON.stringify(data)
        });
        console.log(response);
        const returned = await response.json();
        console.log(returned);
        if (returned.error) {
            console.log(returned.error);
        } else {
            showFavorite(returned);
        }
    }

    const showFavorite = $tip => {
        const $checkbox = document.querySelector('.favorite');
        if ($tip['favorite'] == 1) {
            $checkbox.innerHTML += `checked`
            console.log('checked');
        }
    }

    const handleReactionFocus = e => {
        e.preventDefault();
        const $form = e.currentTarget;
        postReaction($form.getAttribute('action'), formdataToJson($form));
    }

    const postReaction = async (url, data) => {
        const response = await fetch(url, {
            method: "POST",
            headers: new Headers({
                'Content-Type': 'application/json'
            }),
            body: JSON.stringify(data)
        });

        const returned = await response.json();
        console.log(returned);
        if (returned.error) {
            console.log(returned.error);
        } else {
            showReaction(returned);
        }
    }

    const showReaction = reacties => {
        if (reacties['reaction_id'] == 1) {
            const $works = document.querySelector(`.works_text`);
            console.log(reacties);
            $works.innerHTML = `${reacties['result']['aantal']}`
        }
        if (reacties['reaction_id'] == 2) {
            const $d_work = document.querySelector(`.d_work_text`);
            console.log(reacties);
            $d_work.innerHTML = `${reacties['result']['aantal']}`
        }
    }

    const handleCommentSubmit = e => {
        const $form = e.currentTarget;
        e.preventDefault();
        postComment($form.getAttribute('action'), formdataToJson($form));
    }

    const postComment = async (url, data) => {
        const response = await fetch(url, {
            method: "POST",
            headers: new Headers({
                'Content-Type': 'application/json'
            }),
            body: JSON.stringify(data)
        });
        const returned = await response.json();
        console.log(returned);
        if (returned.error) {
            console.log(returned.error);
        } else {
            showComment(returned);
        }
    }

    const showComment = opmerkingen => {
        const $parent = document.querySelector('.box-comments');
        $parent.innerHTML = ``;
        opmerkingen.forEach(opmerking => {
            const $div = document.createElement('div');
            $div.innerHTML = `
                <img class="txt-bubble" src="assets/images/txt_bubble-27.svg" alt="text_bubble" width="40" height="40">
                <p class="text">${opmerking.opmerking}`
            $div.classList.add(`opmerking-box`)
            $parent.appendChild($div);
        })
    }

    const formdataToJson = $form => {
        const data = new FormData($form);
        const obj = {}
        data.forEach((value, key) => {
            console.log(key + ':' + value);
            obj[key] = value;
        });
        console.log(obj);
        return obj;
    }

    const init = () => {
        document.documentElement.classList.add('has-js');

        const $form_comment = document.querySelector('.form_comment');
        //console.log($form_comment);
        if ($form_comment) {
            $form_comment.addEventListener('submit', handleCommentSubmit);
        }

        const $form_reaction = document.querySelector('.form_reaction');
        //console.log($form_reaction);
        if ($form_reaction) {
            $form_reaction.addEventListener('input', handleReactionFocus);
        }

        const $favoritesForm = document.querySelector('.favorites_form');
        if ($favoritesForm) {
            $favoritesForm.addEventListener('input', handleFavoritesInput);
        }

        const $smallFavoritesForm = document.querySelectorAll('.smallFavoritesForm');
        //console.log($smallFavoritesForm);
        if ($smallFavoritesForm) {
            $smallFavoritesForm.forEach($favForm => {
                $favForm.addEventListener('input', handleSmallFavoritesInput);
            });
        }

        const $search = document.querySelector('.search');
        //console.log($search);
        if ($search) {
            $search.addEventListener('input', handleInputSearch);
        }

        const box_sort = document.querySelectorAll('.input_btn');
        box_sort.forEach($filter => {
            console.log($filter.getAttribute('value'));
            if ($filter) {
                $filter.addEventListener('input', handleInputFilter);
            }
        });


        const $sort = document.querySelector('.sort-tips');
        //console.log($filter);
        if ($sort) {
            $sort.addEventListener(`change`, handleChangeSort);
        }
    }

    init();
}