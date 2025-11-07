#!/bin/bash

################################################################################
#                       DIGICAL v1.5 GitHub Upload Script
#                                                                              
# Purpose: Automate uploading DigiCal to GitHub repository                    
# Repository: https://github.com/mcrnlec/digical                             
# Usage: bash upload-to-github.sh [options]                                  
#                                                                              
################################################################################

set -e  # Exit on error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
GITHUB_USERNAME="mcrnlec"
GITHUB_REPO="digical"
GITHUB_URL="https://github.com/${GITHUB_USERNAME}/${GITHUB_REPO}.git"
VERSION="1.5"
BRANCH="main"

################################################################################
# Functions
################################################################################

# Print colored output
print_info() {
    echo -e "${BLUE}ℹ️  $1${NC}"
}

print_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

# Print header
print_header() {
    echo -e "\n${BLUE}════════════════════════════════════════════════════${NC}"
    echo -e "${BLUE}$1${NC}"
    echo -e "${BLUE}════════════════════════════════════════════════════${NC}\n"
}

# Check prerequisites
check_prerequisites() {
    print_header "Checking Prerequisites"
    
    # Check Git
    if ! command -v git &> /dev/null; then
        print_error "Git is not installed"
        echo "Download from: https://git-scm.com/download"
        exit 1
    fi
    print_success "Git is installed: $(git --version)"
    
    # Check if in git directory
    if ! git rev-parse --git-dir > /dev/null 2>&1; then
        print_warning "Not in a Git repository"
        echo "Run: git clone ${GITHUB_URL}"
        exit 1
    fi
    print_success "Git repository detected"
    
    # Check remote
    if ! git remote get-url origin &> /dev/null; then
        print_warning "No remote configured"
        echo "Run: git remote add origin ${GITHUB_URL}"
        exit 1
    fi
    print_success "Git remote configured"
    
    # Check branch
    CURRENT_BRANCH=$(git rev-parse --abbrev-ref HEAD)
    print_success "Current branch: ${CURRENT_BRANCH}"
}

# Check if authenticated
check_authentication() {
    print_header "Checking GitHub Authentication"
    
    if ! git ls-remote origin > /dev/null 2>&1; then
        print_error "Authentication failed"
        echo ""
        echo "Set up authentication:"
        echo "1. Personal Access Token: https://github.com/settings/tokens"
        echo "2. SSH Key: https://github.com/settings/ssh/new"
        echo "3. GitHub CLI: https://cli.github.com"
        exit 1
    fi
    print_success "GitHub authentication successful"
}

# Get Git status
check_git_status() {
    print_header "Checking Git Status"
    
    git status
    
    if [ -z "$(git status --porcelain)" ]; then
        print_info "Working directory is clean"
        return
    fi
    
    print_warning "Uncommitted changes detected"
}

# Add files
add_files() {
    print_header "Adding Files"
    
    print_info "Adding all files..."
    git add .
    
    CHANGES=$(git diff --cached --stat)
    echo "$CHANGES"
    
    print_success "Files staged for commit"
}

# Create commit
create_commit() {
    print_header "Creating Commit"
    
    if [ -z "$(git diff --cached --name-only)" ]; then
        print_warning "No changes to commit"
        return
    fi
    
    COMMIT_MSG="v${VERSION}: Release version ${VERSION} - Speakers module complete

Features:
- Speakers module implementation
- Configuration panel for titles and roles
- Complete documentation suite
- Updated requirements: WordPress 6.8.3+, PHP 8.0+

Files:
- digi-cal.php: Updated plugin header
- 6 admin modules with AJAX handlers
- 6 comprehensive documentation files
- Updated styling and utilities

Refs: GitHub issue #1"
    
    print_info "Commit message:"
    echo "$COMMIT_MSG"
    echo ""
    
    git commit -m "$COMMIT_MSG"
    print_success "Commit created"
}

# Push to GitHub
push_to_github() {
    print_header "Pushing to GitHub"
    
    print_info "Pushing to ${BRANCH} branch..."
    git push origin ${BRANCH}
    print_success "Pushed to GitHub"
}

# Create tag
create_tag() {
    print_header "Creating Release Tag"
    
    # Check if tag exists
    if git rev-parse v${VERSION} > /dev/null 2>&1; then
        print_warning "Tag v${VERSION} already exists"
        read -p "Overwrite? (y/n) " -n 1 -r
        echo
        if [[ $REPLY =~ ^[Yy]$ ]]; then
            git tag -d v${VERSION}
            git push origin --delete v${VERSION} 2>/dev/null || true
        else
            return
        fi
    fi
    
    TAG_MSG="Release v${VERSION}

This is a major release featuring:
- Complete Speakers module
- Updated requirements
- Comprehensive documentation

For details, see CHANGELOG.md and RELEASE_NOTES_v1.5.md"
    
    print_info "Creating tag v${VERSION}..."
    git tag -a v${VERSION} -m "$TAG_MSG"
    
    print_info "Pushing tag to GitHub..."
    git push origin v${VERSION}
    
    print_success "Tag v${VERSION} created and pushed"
}

# Verify upload
verify_upload() {
    print_header "Verifying Upload"
    
    print_info "Checking commit history..."
    git log --oneline -5
    echo ""
    
    print_info "Checking tags..."
    git tag -l
    echo ""
    
    print_info "Checking remote..."
    git remote -v
    echo ""
    
    print_success "Verification complete"
}

# Show summary
show_summary() {
    print_header "Upload Summary"
    
    echo "Repository: ${GITHUB_URL}"
    echo "Username: ${GITHUB_USERNAME}"
    echo "Repository: ${GITHUB_REPO}"
    echo "Branch: ${BRANCH}"
    echo "Version: ${VERSION}"
    echo ""
    echo "Next steps:"
    echo "1. Visit: https://github.com/${GITHUB_USERNAME}/${GITHUB_REPO}"
    echo "2. Check: https://github.com/${GITHUB_USERNAME}/${GITHUB_REPO}/releases"
    echo "3. Share the release with your team"
    echo ""
    print_success "Upload complete! 🎉"
}

# Main menu
show_menu() {
    echo ""
    echo "What would you like to do?"
    echo ""
    echo "1. Full upload (check → add → commit → push → tag)"
    echo "2. Check prerequisites only"
    echo "3. Add files and commit"
    echo "4. Push to GitHub"
    echo "5. Create/update tag"
    echo "6. Verify upload"
    echo "7. Exit"
    echo ""
    read -p "Enter option (1-7): " option
}

################################################################################
# Main Script
################################################################################

main() {
    echo ""
    echo -e "${BLUE}╔════════════════════════════════════════════════════╗${NC}"
    echo -e "${BLUE}║     DigiCal v1.5 GitHub Upload Script              ║${NC}"
    echo -e "${BLUE}║     Repository: ${GITHUB_REPO}                          ║${NC}"
    echo -e "${BLUE}╚════════════════════════════════════════════════════╝${NC}"
    echo ""
    
    # Check if arguments provided
    if [ $# -gt 0 ]; then
        case "$1" in
            full)
                check_prerequisites
                check_authentication
                check_git_status
                add_files
                create_commit
                push_to_github
                create_tag
                verify_upload
                show_summary
                ;;
            check)
                check_prerequisites
                check_authentication
                ;;
            add)
                add_files
                ;;
            commit)
                create_commit
                ;;
            push)
                push_to_github
                ;;
            tag)
                create_tag
                ;;
            verify)
                verify_upload
                ;;
            help)
                echo "Usage: bash upload-to-github.sh [option]"
                echo ""
                echo "Options:"
                echo "  full      - Complete upload (recommended)"
                echo "  check     - Check prerequisites only"
                echo "  add       - Add files"
                echo "  commit    - Create commit"
                echo "  push      - Push to GitHub"
                echo "  tag       - Create release tag"
                echo "  verify    - Verify upload"
                echo "  help      - Show this help"
                echo ""
                echo "Example:"
                echo "  bash upload-to-github.sh full"
                ;;
            *)
                print_error "Unknown option: $1"
                echo "Use 'bash upload-to-github.sh help' for usage"
                exit 1
                ;;
        esac
    else
        # Interactive mode
        while true; do
            show_menu
            case $option in
                1)
                    check_prerequisites
                    check_authentication
                    check_git_status
                    add_files
                    create_commit
                    push_to_github
                    create_tag
                    verify_upload
                    show_summary
                    break
                    ;;
                2)
                    check_prerequisites
                    check_authentication
                    ;;
                3)
                    add_files
                    create_commit
                    ;;
                4)
                    push_to_github
                    ;;
                5)
                    create_tag
                    ;;
                6)
                    verify_upload
                    ;;
                7)
                    print_info "Exiting..."
                    exit 0
                    ;;
                *)
                    print_error "Invalid option"
                    ;;
            esac
        done
    fi
}

# Run main function
main "$@"
