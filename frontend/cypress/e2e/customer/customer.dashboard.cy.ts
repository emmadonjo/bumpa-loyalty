import {UserAchievement} from "@/types";

describe('Customer Dashboard Tests', () => {
    beforeEach(() => {
        cy.login('customer');
        cy.fixture('user').as('user');
        cy.fixture('user-achievements').as('userAchievements');
    });


    it('should display all loyalty stats correctly', function () {
        cy.intercept('GET', '**/api/users/2*', {
            statusCode: 200,
            body: {
                status: true,
                message: '',
                data: this.user,
            }
        }).as('getUserStats');
        cy.visit('/');
        cy.wait('@getUserStats');

        const stats = [
            { label: 'Tot. Achievements', value: String(this.user.achievements_count), index: 1 },
            { label: 'Badges', value: String(this.user.badges_count), index: 2 },
            { label: 'Purchases', value: String(this.user.loyalty_info?.purchase_count ?? 0), index: 3 },
            { label: 'Tot. Spent', value: "₦50,000.00", index: 4 },
            { label: 'Loyalty Balance', value: "₦1,500.00", index: 5 }
        ];

        stats.forEach(stat => {
            cy.get(`.loyalty-stats`).contains('h4', stat.value).should('be.visible');
            cy.get(`.loyalty-stats`).contains('span', stat.label).should('be.visible');
        });
    });

    it('should display customer badge progress', function() {
        cy.intercept('GET', '**/api/users/2*', {
            statusCode: 200,
            body: {
                status: true,
                message: '',
                data: this.user,
            }
        }).as('getUserStats');
        cy.visit('/');
        cy.wait('@getUserStats');

        const expectedBadges = ['Newcomer', 'Bronze', 'Silver', 'Gold', 'Platinum'];

        cy.get('.user-badges ul > li').each(($el, index) => {
            cy.wrap($el).should('contain', expectedBadges[index]);

            if (index !== 0) {
                cy.wrap($el).should('have.class', 'text-default-100');
            } else {
                cy.wrap($el).should('not.have.class', 'text-default-100');
            }
        });
    });


    it("should display a customer's achievements", function () {
        cy.intercept('GET', '**/api/users/2/achievements*', {
            statusCode: 200,
            body: {
                status: true,
                message: '',
                data: this.userAchievements,
                meta: {current_page: 1, last_page: 1, total: 10, per_page: 20},
            }
        }).as('getUserAchievements');
        cy.visit('/');
        cy.wait('@getUserAchievements');

        cy.get('.userAchievements table tbody tr')
            .should('have.length', this.userAchievements.length);

        const achievements: UserAchievement[] = this.userAchievements;
        achievements.forEach(achievement => {
            cy.contains('.userAchievements table tbody tr',achievement.achievement ? achievement.achievement.name : '')
                .should('be.visible');
            cy.contains('.userAchievements table tbody tr', achievement.achievement ? achievement.achievement.type.replace('_', ' ') : '')
                .should('be.visible');
        });
    });
});