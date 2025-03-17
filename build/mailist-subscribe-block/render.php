<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
?>
<div <?php echo get_block_wrapper_attributes(); ?>>
    <!-- Subscription Form -->
    <form onsubmit="subscribe(event)">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br><br>
        <button type="submit">Subscribe</button>
    </form>
</div>

<script>
    function subscribe(event) {
        // Prevent form submission
        event.preventDefault();

        const email = document.getElementById("email").value;
        const listId = "<?php echo esc_html( $attributes['listId'] ); ?>"; // Replace with actual list_id

        // Adding a loading state to show while the request is being processed
        const button = document.querySelector("button");
        button.innerHTML = "Subscribing..."; // Change button text
        button.disabled = true; // Disable the button to prevent multiple submissions

        fetch('https://mailist.luova.club/signup', {
            method: "POST",
            headers: {
                'Content-Type': "application/json"
            },
            body: JSON.stringify({
                email: email,
                list_id: listId
            })
        })
        .then(response => response.json())
        .then(data => {
            // Handle the response for success or failure
            if (!data.error) {
                showAlert("Subscription successful!", "success");
            } else {
                showAlert(`Subscription failed: ${data.message}`, "error");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            showAlert("An error occurred. Please try again.", "error");
        })
        .finally(() => {
            // Re-enable the button after the request is complete
            button.innerHTML = "Subscribe";
            button.disabled = false;
        });
    }

    function showAlert(message, type) {
        const alertContainer = document.createElement("div");
        alertContainer.classList.add("alert", type);
        alertContainer.innerText = message;

        // Add the alert to the body and then remove it after 5 seconds
        document.body.appendChild(alertContainer);

        setTimeout(() => {
            alertContainer.classList.add("fade-out");
            setTimeout(() => alertContainer.remove(), 500);
        }, 5000);
    }
</script>

<style>
    /* Styling the alert box */
    .alert {
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
        padding: 15px;
        border-radius: 5px;
        font-size: 16px;
        font-weight: bold;
        z-index: 1000;
        transition: opacity 0.5s ease;
    }

    .alert.success {
        background-color: #d4edda;
        color: #155724;
        border-color: #c3e6cb;
    }

    .alert.error {
        background-color: #f8d7da;
        color: #721c24;
        border-color: #f5c6cb;
    }

    .alert.fade-out {
        opacity: 0;
    }

    /* Button transition */
    button {
        transition: background-color 0.3s ease;
    }

    button:disabled {
        background-color: #ccc;
    }
</style>

