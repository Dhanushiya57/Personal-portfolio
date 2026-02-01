# Personal Portfolio Website

A modern, responsive personal portfolio website showcasing skills, experience, education, and projects.

## Features

- **Responsive Design**: Fully responsive layout that works on all devices
- **Modern UI/UX**: Clean and professional design with smooth animations
- **Dark Theme**: Eye-friendly dark theme with gradient accents
- **Interactive Elements**: Smooth scrolling, animated skill bars, and hover effects
- **Contact Form**: Functional contact form for visitor inquiries
- **Social Media Integration**: Links to LinkedIn, GitHub, and email

## Sections

1. **Home/Hero**: Introduction with call-to-action buttons
2. **About**: Personal information and downloadable CV
3. **Skills**: Technical skills organized by categories with progress bars
4. **Experience**: Professional experience timeline
5. **Education**: Educational background
6. **Projects**: Portfolio of featured projects
7. **Contact**: Contact form and information

## Technologies Used

- HTML5
- CSS3 (with CSS Variables and Flexbox/Grid)
- JavaScript (Vanilla JS)
- Font Awesome Icons
- Google Fonts (Poppins)

## Setup Instructions

1. Place all files in your web server directory (e.g., `htdocs` for XAMPP)
2. Add your profile image to `images/profile.jpg`
3. Add project images to the `images/` folder (project1.jpg, project2.jpg, etc.)
4. Update personal information in `index.html`:
   - Contact details (email, phone)
   - Social media links
   - Experience details
   - Education information
   - Project information

## Customization

### Colors
Edit the CSS variables in `css/style.css` to change the color scheme:
```css
:root {
    --primary-color: #6366f1;
    --secondary-color: #8b5cf6;
    --accent-color: #ec4899;
    /* ... other colors */
}
```

### Content
- Update text content directly in `index.html`
- Modify skill levels by changing the `width` percentage in skill progress bars
- Add/remove sections as needed

### Contact Form
Currently, the form displays an alert on submission. To make it functional:
1. Set up a backend service (PHP, Node.js, etc.)
2. Or use a third-party service like Formspree or EmailJS
3. Update the form submission handler in `js/script.js`

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers

## To-Do

- [ ] Add your profile photo
- [ ] Add project screenshots
- [ ] Update contact information
- [ ] Connect contact form to backend/service
- [ ] Add your GitHub profile link
- [ ] Update experience and education details
- [ ] Add real project links

## License

Feel free to use this template for your personal portfolio!

## Contact

For any questions or suggestions, please reach out through the contact form on the website.
