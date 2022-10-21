"use strict";

class HnLoadMore extends elementorModules.frontend.handlers.Base {
      getDefaultSettings() {
            return {
                  selectors: {
                        postsContainer: '.elementor-posts-container',
                        loadMoreButton: '.elementor-button',
                        loadMoreSpinnerWrapper: '.e-load-more-spinner',
                        loadMoreSpinner: '.e-load-more-spinner i, .e-load-more-spinner svg',
                        loadMoreAnchor: '.e-load-more-anchor'
                  },
                  classes: {
                        loadMoreSpin: 'eicon-animation-spin',
                        loadMoreIsLoading: 'e-load-more-pagination-loading',
                        loadMorePaginationEnd: 'e-load-more-pagination-end',
                        loadMoreNoSpinner: 'e-load-more-no-spinner'
                  }
            };
      }

      getDefaultElements() {
            const selectors = this.getSettings('selectors');
            return {
                  postsWidgetWrapper: this.$element[0],
                  postsContainer: this.$element[0].querySelector(selectors.postsContainer),
                  loadMoreButton: this.$element[0].querySelector(selectors.loadMoreButton),
                  loadMoreSpinnerWrapper: this.$element[0].querySelector(selectors.loadMoreSpinnerWrapper),
                  loadMoreSpinner: this.$element[0].querySelector(selectors.loadMoreSpinner),
                  loadMoreAnchor: this.$element[0].querySelector(selectors.loadMoreAnchor)
            };
      }

      bindEvents() {
            super.bindEvents();

            if (!this.elements.loadMoreButton) {
                  return;
            }

            this.elements.loadMoreButton.addEventListener('click', event => {
                  if (this.isLoading) {
                        return;
                  }

                  event.preventDefault();
                  this.handlePostsQuery();
            });
      }

      onInit() {
            super.onInit();
            this.classes = this.getSettings('classes');
            this.isLoading = false;
            const paginationType = this.getElementSettings('pagination_type');

            if ('load_more_on_click' !== paginationType && 'load_more_infinite_scroll' !== paginationType) {
                  return;
            }

            this.isInfinteScroll = 'load_more_infinite_scroll' === paginationType;

            this.isSpinnerAvailable = this.getElementSettings('load_more_spinner').value;

            if (!this.isSpinnerAvailable) {
                  this.elements.postsWidgetWrapper.classList.add(this.classes.loadMoreNoSpinner);
            }

            if (this.isInfinteScroll) {
                  this.handleInfiniteScroll();
            } else if (this.elements.loadMoreSpinnerWrapper && this.elements.loadMoreButton) {
                  this.elements.loadMoreButton.insertAdjacentElement('beforeEnd', this.elements.loadMoreSpinnerWrapper);
            }


            this.elementId = this.getID();
            this.postId = elementorFrontendConfig.post.id;

            if (this.elements.loadMoreAnchor) {
                  this.currentPage = parseInt(this.elements.loadMoreAnchor.getAttribute('data-page'));
                  this.maxPage = parseInt(this.elements.loadMoreAnchor.getAttribute('data-max-page'));

                  if (this.currentPage === this.maxPage || !this.currentPage) {
                        this.handleUiWhenNoPosts();
                  }
            }
      }


      handleInfiniteScroll() {
            if (this.isEdit) {
                  return;
            }

            this.observer = elementorModules.utils.Scroll.scrollObserver({
                  callback: event => {
                        if (!event.isInViewport || this.isLoading) {
                              return;
                        }


                        this.observer.unobserve(this.elements.loadMoreAnchor);
                        this.handlePostsQuery().then(() => {
                              if (this.currentPage !== this.maxPage) {
                                    this.observer.observe(this.elements.loadMoreAnchor);
                              }
                        });
                  }
            });
            this.observer.observe(this.elements.loadMoreAnchor);
      }

      handleUiBeforeLoading() {
            this.isLoading = true;

            if (this.elements.loadMoreSpinner) {
                  this.elements.loadMoreSpinner.classList.add(this.classes.loadMoreSpin);
            }

            this.elements.postsWidgetWrapper.classList.add(this.classes.loadMoreIsLoading);
      }

      handleUiAfterLoading() {
            this.isLoading = false;

            if (this.elements.loadMoreSpinner) {
                  this.elements.loadMoreSpinner.classList.remove(this.classes.loadMoreSpin);
            }

            if (this.isInfinteScroll && this.elements.loadMoreSpinnerWrapper && this.elements.loadMoreAnchor) {
                  this.elements.loadMoreAnchor.insertAdjacentElement('afterend', this.elements.loadMoreSpinnerWrapper);
            }

            this.elements.postsWidgetWrapper.classList.remove(this.classes.loadMoreIsLoading);
      }

      handleUiWhenNoPosts() {
            this.elements.postsWidgetWrapper.classList.add(this.classes.loadMorePaginationEnd);
      }

      handleSuccessFetch(result) {
            this.handleUiAfterLoading();

            const posts = result.querySelectorAll(`[data-id="${this.elementId}"] .elementor-posts-container > article`);
            const nextPageUrl = result.querySelector('.e-load-more-anchor').getAttribute('data-next-page');

            const postsHTML = [...posts].reduce((accumulator, post) => {
                  return accumulator + post.outerHTML;
            }, '');
            this.elements.postsContainer.insertAdjacentHTML('beforeend', postsHTML);
            this.elements.loadMoreAnchor.setAttribute('data-page', this.currentPage);
            this.elements.loadMoreAnchor.setAttribute('data-next-page', nextPageUrl);

            if (this.currentPage === this.maxPage) {
                  this.handleUiWhenNoPosts();
            }
      }

      handlePostsQuery() {
            this.handleUiBeforeLoading();
            this.currentPage++;
            const nextPageUrl = this.elements.loadMoreAnchor.getAttribute('data-next-page');
            return fetch(nextPageUrl).then(response => response.text()).then(html => {
                  const parser = new DOMParser();
                  const doc = parser.parseFromString(html, 'text/html');
                  this.handleSuccessFetch(doc);
            }).catch(err => {
                  console.warn('Something went wrong.', err);
            });
      }
}

jQuery(window).on('elementor/frontend/init', () => {
      const addHandler = ($element) => {
            elementorFrontend.elementsHandler.addHandler(HnLoadMore, {
                  $element,
            });
            console.log($element);
      };

      elementorFrontend.hooks.addAction('frontend/element_ready/posts.hn-custom-skin', addHandler);
});