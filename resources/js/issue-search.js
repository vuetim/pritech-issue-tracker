const filterForm = document.querySelector("#issue-filter-form");

if (filterForm) {
    const searchInput = document.querySelector("#issue-search");
    const results = document.querySelector("#issues-results");

    let debounceTimer;
    let activeRequest;

    const buildSearchUrl = () => {
        const parameters = new URLSearchParams();

        new FormData(filterForm).forEach((value, key) => {
            const normalizedValue = String(value).trim();

            if (normalizedValue) {
                parameters.set(key, normalizedValue);
            }
        });

        const query = parameters.toString();

        return query ? `${filterForm.action}?${query}` : filterForm.action;
    };

    const loadResults = async (url = buildSearchUrl()) => {
        activeRequest?.abort();

        const currentRequest = new AbortController();
        activeRequest = currentRequest;

        results.setAttribute("aria-busy", "true");
        results.classList.add("opacity-60");

        try {
            const response = await fetch(url, {
                headers: {
                    Accept: "application/json",
                },
                signal: currentRequest.signal,
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message ?? "Unable to search issues.");
            }

            results.innerHTML = data.html;
            window.history.replaceState({}, "", url);
        } catch (error) {
            if (error.name !== "AbortError") {
                results.innerHTML = `
                    <div class="rounded-lg bg-red-50 p-4 text-sm text-red-700">
                        ${error.message}
                    </div>
                `;
            }
        } finally {
            if (activeRequest === currentRequest) {
                results.removeAttribute("aria-busy");
                results.classList.remove("opacity-60");
            }
        }
    };

    searchInput.addEventListener("input", () => {
        window.clearTimeout(debounceTimer);

        debounceTimer = window.setTimeout(() => {
            loadResults();
        }, 350);
    });

    filterForm.addEventListener("submit", (event) => {
        event.preventDefault();
        loadResults();
    });

    results.addEventListener("click", (event) => {
        const paginationLink = event.target.closest(
            "[data-pagination] a[href]",
        );
        if (!paginationLink) {
            return;
        }

        event.preventDefault();
        loadResults(paginationLink.href);
    });
}
