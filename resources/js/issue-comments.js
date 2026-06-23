const commentsPanel = document.querySelector("#comments-panel");

if (commentsPanel) {
    const form = document.querySelector("#comment-form");
    const commentsList = document.querySelector("#comments-list");
    const commentsCount = document.querySelector("#comments-count");
    const loadMoreButton = document.querySelector("#load-more-comments");
    const feedback = document.querySelector("#comment-feedback");
    const submitButton = form.querySelector('button[type="submit"]');
    const authorInput = document.querySelector("#author_name");
    const bodyInput = document.querySelector("#body");
    const authorError = document.querySelector("#author-name-error");
    const bodyError = document.querySelector("#body-error");
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    let nextPageUrl = null;

    const showFeedback = (message, type = "success") => {
        feedback.textContent = message;
        feedback.className =
            type === "success"
                ? "mt-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-800"
                : "mt-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-800";
    };

    const clearErrors = () => {
        authorError.textContent = "";
        authorError.classList.add("hidden");

        bodyError.textContent = "";
        bodyError.classList.add("hidden");
    };

    const showValidationErrors = (errors) => {
        if (errors.author_name?.[0]) {
            authorError.textContent = errors.author_name[0];
            authorError.classList.remove("hidden");
        }

        if (errors.body?.[0]) {
            bodyError.textContent = errors.body[0];
            bodyError.classList.remove("hidden");
        }
    };

    const updateLoadMoreButton = () => {
        loadMoreButton.classList.toggle("hidden", !nextPageUrl);
    };

    const loadComments = async (url, append = false) => {
        loadMoreButton.disabled = true;

        try {
            const response = await fetch(url, {
                headers: {
                    Accept: "application/json",
                },
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message ?? "Unable to load comments.");
            }

            if (append) {
                commentsList.insertAdjacentHTML("beforeend", data.html);
            } else {
                commentsList.innerHTML = data.html;
            }

            commentsCount.textContent = `(${data.total})`;
            nextPageUrl = data.next_page_url;
            updateLoadMoreButton();
        } catch (error) {
            showFeedback(error.message, "error");

            if (!append) {
                commentsList.innerHTML =
                    '<p class="text-sm text-red-600">Unable to load comments.</p>';
            }
        } finally {
            loadMoreButton.disabled = false;
        }
    };

    loadMoreButton.addEventListener("click", () => {
        if (nextPageUrl) {
            loadComments(nextPageUrl, true);
        }
    });

    form.addEventListener("submit", async (event) => {
        event.preventDefault();
        clearErrors();

        submitButton.disabled = true;

        try {
            const response = await fetch(commentsPanel.dataset.storeUrl, {
                method: "POST",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify({
                    author_name: authorInput.value,
                    body: bodyInput.value,
                }),
            });

            const data = await response.json();

            if (response.status === 422) {
                showValidationErrors(data.errors);
                return;
            }

            if (!response.ok) {
                throw new Error(data.message ?? "Unable to add comment.");
            }

            commentsList.querySelector("[data-empty-comments]")?.remove();

            commentsList.insertAdjacentHTML("afterbegin", data.html);

            const commentItems =
                commentsList.querySelectorAll("[data-comment]");

            commentItems.forEach((comment, index) => {
                if (index >= 5) {
                    comment.remove();
                }
            });

            if (data.total > 5) {
                const pageTwoUrl = new URL(
                    commentsPanel.dataset.indexUrl,
                    window.location.origin,
                );

                pageTwoUrl.searchParams.set("page", "2");
                nextPageUrl = pageTwoUrl.toString();
            } else {
                nextPageUrl = null;
            }

            updateLoadMoreButton();
            commentsCount.textContent = `(${data.total})`;
            form.reset();
            showFeedback(data.message);
        } catch (error) {
            showFeedback(error.message, "error");
        } finally {
            submitButton.disabled = false;
        }
    });

    loadComments(commentsPanel.dataset.indexUrl);
}
