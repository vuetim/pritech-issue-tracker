const form = document.querySelector("#assign-member-form");

if (form) {
    const select = document.querySelector("#member-select");
    const membersContainer = document.querySelector("#issue-members");
    const emptyMessage = document.querySelector("#empty-members-message");
    const feedback = document.querySelector("#member-feedback");
    const errorMessage = document.querySelector("#member-error");
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
        const hasMembers = membersContainer.querySelector("[data-member-item]");

        emptyMessage.classList.toggle("hidden", Boolean(hasMembers));
    };

    const createMemberElement = (member) => {
        const item = document.createElement("div");

        item.dataset.memberItem = "";
        item.dataset.memberId = member.id;
        item.dataset.memberName = member.name;
        item.dataset.memberEmail = member.email;
        item.className =
            "flex items-center justify-between rounded-lg bg-slate-50 px-4 py-3";

        const details = document.createElement("div");

        const name = document.createElement("div");
        name.className = "font-medium text-slate-900";
        name.textContent = member.name;

        const email = document.createElement("div");
        email.className = "text-sm text-slate-500";
        email.textContent = member.email;

        details.append(name, email);

        const removeButton = document.createElement("button");
        removeButton.type = "button";
        removeButton.dataset.detachMember = "";
        removeButton.dataset.url = detachTemplate.replace(
            "__USER__",
            String(member.id),
        );
        removeButton.className =
            "text-sm font-semibold text-red-600 hover:text-red-800";
        removeButton.textContent = "Remove";

        item.append(details, removeButton);

        return item;
    };

    form.addEventListener("submit", async (event) => {
        event.preventDefault();
        clearError();

        if (!select.value) {
            showError("Please select a member.");
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
                    user_id: select.value,
                }),
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(
                    data.errors?.user_id?.[0] ??
                        data.message ??
                        "Unable to assign member.",
                );
            }

            const existingMember = membersContainer.querySelector(
                `[data-member-id="${data.member.id}"]`,
            );

            if (!existingMember) {
                membersContainer.append(createMemberElement(data.member));
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

    membersContainer.addEventListener("click", async (event) => {
        const button = event.target.closest("[data-detach-member]");

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
                throw new Error(data.message ?? "Unable to remove member.");
            }

            const item = button.closest("[data-member-item]");
            const option = document.createElement("option");

            option.value = item.dataset.memberId;
            option.textContent = `${item.dataset.memberName} — ${item.dataset.memberEmail}`;

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
