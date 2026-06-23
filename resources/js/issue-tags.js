const form = document.querySelector("#attach-tag-form");

if (form) {
    const select = document.querySelector("#tag-select");
    const tagsContainer = document.querySelector("#issue-tags");
    const emptyMessage = document.querySelector("#empty-tags-message");
    const feedback = document.querySelector("#tag-feedback");
    const errorMessage = document.querySelector("#tag-error");
    const submitButton = form.querySelector('button[type="submit"]');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    const detachTemplate = form.dataset.detachTemplate;

    const showFeedback = (message, type = "success") => {
        feedback.textContent = message;
        feedback.className =
            type === "success"
                ? "mt-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-800"
                : "mt-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-800";
    };

    const clearError = () => {
        errorMessage.textContent = "";
        errorMessage.classList.add("hidden");
    };

    const showError = (message) => {
        errorMessage.textContent = message;
        errorMessage.classList.remove("hidden");
    };

    const updateEmptyState = () => {
        const hasTags = tagsContainer.querySelector("[data-tag-item]");

        emptyMessage.classList.toggle("hidden", Boolean(hasTags));
    };

    const createTagElement = (tag) => {
        const item = document.createElement("span");

        item.dataset.tagItem = "";
        item.dataset.tagId = tag.id;
        item.dataset.tagName = tag.name;
        item.className =
            "inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-sm text-slate-700";

        const color = document.createElement("span");
        color.className = "h-2.5 w-2.5 rounded-full";
        color.style.backgroundColor = tag.color || "#cbd5e1";

        const name = document.createElement("span");
        name.textContent = tag.name;

        const detachButton = document.createElement("button");
        detachButton.type = "button";
        detachButton.dataset.detachTag = "";
        detachButton.dataset.url = detachTemplate.replace(
            "__TAG__",
            String(tag.id),
        );
        detachButton.className = "font-bold text-slate-400 hover:text-red-600";
        detachButton.setAttribute("aria-label", `Detach ${tag.name}`);
        detachButton.textContent = "×";

        item.append(color, name, detachButton);

        return item;
    };

    form.addEventListener("submit", async (event) => {
        event.preventDefault();
        clearError();

        if (!select.value) {
            showError("Please select a tag.");
            return;
        }

        submitButton.disabled = true;

        try {
            const response = await fetch(form.dataset.url, {
                method: "POST",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify({
                    tag_id: select.value,
                }),
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(
                    data.errors?.tag_id?.[0] ??
                        data.message ??
                        "Unable to attach tag.",
                );
            }

            const existingTag = tagsContainer.querySelector(
                `[data-tag-id="${data.tag.id}"]`,
            );

            if (!existingTag) {
                tagsContainer.append(createTagElement(data.tag));
            }

            select.options[select.selectedIndex].remove();
            select.value = "";

            updateEmptyState();
            showFeedback(data.message);
        } catch (error) {
            showError(error.message);
        } finally {
            submitButton.disabled = false;
        }
    });

    tagsContainer.addEventListener("click", async (event) => {
        const button = event.target.closest("[data-detach-tag]");

        if (!button) {
            return;
        }

        clearError();
        button.disabled = true;

        try {
            const response = await fetch(button.dataset.url, {
                method: "DELETE",
                headers: {
                    Accept: "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message ?? "Unable to detach tag.");
            }

            const item = button.closest("[data-tag-item]");
            const option = document.createElement("option");

            option.value = item.dataset.tagId;
            option.textContent = item.dataset.tagName;
            select.append(option);

            item.remove();

            updateEmptyState();
            showFeedback(data.message);
        } catch (error) {
            showError(error.message);
            button.disabled = false;
        }
    });
}
