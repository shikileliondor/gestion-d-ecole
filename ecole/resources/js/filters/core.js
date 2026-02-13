const FILTER_FORM_SELECTOR = '[data-filter-form]';
const FILTER_SELECT_SELECTOR = 'select[data-filter], select[name]';

function ensureFilterIdentity(select) {
    if (!select.dataset.filter) {
        const baseName = select.name?.replace(/\[\]$/, '') ?? 'filter';
        select.dataset.filter = baseName;
    }

    return select.dataset.filter;
}

function ensureAllOption(select) {
    if (select.multiple || select.required || select.dataset.filterAll === 'false') {
        return;
    }

    const hasEmptyOption = Array.from(select.options).some((option) => option.value === '');
    if (hasEmptyOption) {
        return;
    }

    const emptyOption = new Option(select.dataset.filterAllLabel || 'Tous', '', false, false);
    select.insertBefore(emptyOption, select.firstChild);
}

function resolvePlaceholder(select) {
    if (select.dataset.placeholder) {
        return select.dataset.placeholder;
    }

    const id = select.id;
    const label = id ? document.querySelector(`label[for="${id}"]`) : null;
    const labelText = label?.textContent?.trim();

    return labelText ? `Tous · ${labelText}` : 'Tout';
}

function initSelect2(select) {
    if (!window.jQuery || !window.jQuery.fn?.select2) {
        return;
    }

    const $ = window.jQuery;
    const placeholder = resolvePlaceholder(select);

    $(select).select2({
        width: '100%',
        placeholder,
        allowClear: !select.required,
        closeOnSelect: !select.multiple,
    });
}

export function initFilterForm(form) {
    const selects = form.querySelectorAll(FILTER_SELECT_SELECTOR);

    selects.forEach((select) => {
        ensureFilterIdentity(select);
        ensureAllOption(select);
        initSelect2(select);
    });
}

export function initAllFilterForms(scope = document) {
    scope.querySelectorAll(FILTER_FORM_SELECTOR).forEach(initFilterForm);
}

export function resetFilterForm(form) {
    form.querySelectorAll(FILTER_SELECT_SELECTOR).forEach((select) => {
        select.value = select.multiple ? [] : '';

        if (window.jQuery && window.jQuery.fn?.select2) {
            window.jQuery(select).val(select.multiple ? [] : '').trigger('change');
        }
    });
}

window.FilterUI = {
    initAll: initAllFilterForms,
    resetForm: resetFilterForm,
};
