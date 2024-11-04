export function watchStartEndDates(formData) {
    return () => {
        if (!formData.start_at) {
            return;
        }

        if (formData.end_at < formData.start_at) {
            formData.end_at = '';
        }

        document.querySelector('#event-end-at').min = formData.start_at;
    };
}
