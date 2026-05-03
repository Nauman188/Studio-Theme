/*!
 * Start Bootstrap - Agency v7.0.12 (https://startbootstrap.com/theme/agency)
 * Copyright 2013-2023 Start Bootstrap
 * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-agency/blob/master/LICENSE)
 */
//
// Scripts
//

window.addEventListener("DOMContentLoaded", (event) => {
  // Navbar shrink function
  var navbarShrink = function () {
    const navbarCollapsible = document.body.querySelector("#mainNav");
    if (!navbarCollapsible) {
      return;
    }
    if (window.scrollY === 0) {
      navbarCollapsible.classList.remove("navbar-shrink");
    } else {
      navbarCollapsible.classList.add("navbar-shrink");
    }
  };

  // Shrink the navbar
  navbarShrink();

  // Shrink the navbar when page is scrolled
  document.addEventListener("scroll", navbarShrink);

  //  Activate Bootstrap scrollspy on the main nav element
  const mainNav = document.body.querySelector("#mainNav");
  if (mainNav) {
    new bootstrap.ScrollSpy(document.body, {
      target: "#mainNav",
      rootMargin: "0px 0px -40%",
    });
  }

  // Collapse responsive navbar when toggler is visible
  const navbarToggler = document.body.querySelector(".navbar-toggler");
  const responsiveNavItems = [].slice.call(
    document.querySelectorAll("#navbarResponsive .nav-link"),
  );
  responsiveNavItems.map(function (responsiveNavItem) {
    responsiveNavItem.addEventListener("click", () => {
      if (window.getComputedStyle(navbarToggler).display !== "none") {
        navbarToggler.click();
      }
    });
  });

  // --- NEW CONTACT FORM AJAX CODE ---
  const contactForm = document.getElementById("contactForm");
  if (contactForm) {
    contactForm.addEventListener("submit", function (e) {
      e.preventDefault();

      // Get the submit button to show loading state
      const submitBtn = contactForm.querySelector('button[type="submit"]');
      const originalBtnText = submitBtn.innerHTML;
      submitBtn.disabled = true;
      submitBtn.innerHTML = "Sending...";

      // We use jQuery here because it handles form serialization
      // and the localized admin-ajax.php URL very easily in WordPress.
      jQuery.ajax({
        type: "POST",
        url: contact_ajax.ajax_url, // Defined via wp_localize_script in functions.php
        data: jQuery(this).serialize() + "&action=save_contact_form",
        success: function (response) {
          if (response.success) {
            alert(response.data);
            contactForm.reset();
          } else {
            alert("Error: " + response.data);
          }
        },
        error: function () {
          alert("An error occurred. Please try again later.");
        },
        complete: function () {
          submitBtn.disabled = false;
          submitBtn.innerHTML = originalBtnText;
        },
      });
    });
  }
});
// Handle WordPress Dropdowns for Bootstrap 5
const menuItems = document.querySelectorAll(
  ".navbar-nav .menu-item-has-children",
);

menuItems.forEach((item) => {
  item.classList.add("dropdown"); // Add Bootstrap class
  const link = item.querySelector("a");
  if (link) {
    link.classList.add("dropdown-toggle");
    link.setAttribute("data-bs-toggle", "dropdown");
    link.setAttribute("aria-expanded", "false");
  }

  const subMenu = item.querySelector(".sub-menu");
  if (subMenu) {
    subMenu.classList.add("dropdown-menu");
    // Add Bootstrap classes to sub-links
    const subLinks = subMenu.querySelectorAll("a");
    subLinks.forEach((subLink) => subLink.classList.add("dropdown-item"));
  }
});
