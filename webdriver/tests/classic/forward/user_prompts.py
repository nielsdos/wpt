# META: timeout=long

import pytest
from webdriver import error

from tests.support.asserts import assert_dialog_handled, assert_error, assert_success


def forward(session):
    return session.transport.send(
        "POST", "session/{session_id}/forward".format(**vars(session)))


@pytest.fixture
def pages(session, inline):
    pages = [
        inline("<p id=1>"),
        inline("<p id=2>"),
    ]

    for page in pages:
        session.url = page

    session.back()

    return pages


@pytest.fixture
def check_beforeunload_implicitly_accepted(session, url):
    def check_beforeunload_implicitly_accepted():
        page_beforeunload = url(
            "/webdriver/tests/support/html/beforeunload.html")
        page_target = url("/webdriver/tests/support/html/default.html")

        session.url = page_beforeunload
        session.url = page_target
        session.back()

        element = session.find.css("input", all=False)
        element.send_keys("bar")

        response = forward(session)
        assert_success(response)

        assert session.url == page_target

        # navigation auto-dismissed beforeunload prompt
        with pytest.raises(error.NoSuchAlertException):
            session.alert.text

    return check_beforeunload_implicitly_accepted


@pytest.fixture
def check_user_prompt_closed_without_exception(session, create_dialog, pages):
    def check_user_prompt_closed_without_exception(dialog_type, retval):
        create_dialog(dialog_type, text=dialog_type)

        response = forward(session)
        assert_success(response)

        # retval not testable for confirm and prompt because window is gone
        assert_dialog_handled(session, expected_text=dialog_type, expected_retval=None)

        assert session.url == pages[1]

    return check_user_prompt_closed_without_exception


@pytest.fixture
def check_user_prompt_closed_with_exception(session, create_dialog, pages):
    def check_user_prompt_closed_with_exception(dialog_type, retval):
        create_dialog(dialog_type, text=dialog_type)

        response = forward(session)
        assert_error(response, "unexpected alert open")

        assert_dialog_handled(session, expected_text=dialog_type, expected_retval=retval)

        assert session.url == pages[0]

    return check_user_prompt_closed_with_exception


@pytest.fixture
def check_user_prompt_not_closed_but_exception(session, create_dialog, pages):
    def check_user_prompt_not_closed_but_exception(dialog_type):
        create_dialog(dialog_type, text=dialog_type)

        response = forward(session)
        assert_error(response, "unexpected alert open")

        assert session.alert.text == dialog_type
        session.alert.dismiss()

        assert session.url == pages[0]

    return check_user_prompt_not_closed_but_exception


@pytest.mark.capabilities({"unhandledPromptBehavior": "accept"})
@pytest.mark.parametrize("dialog_type", ["alert", "beforeunload", "confirm", "prompt"])
def test_accept(
    check_beforeunload_implicitly_accepted,
    check_user_prompt_closed_without_exception,
    dialog_type
):
    if dialog_type == "beforeunload":
        check_beforeunload_implicitly_accepted()
    else:
        # retval not testable for confirm and prompt because window is gone
        check_user_prompt_closed_without_exception(dialog_type, None)


@pytest.mark.capabilities({"unhandledPromptBehavior": "accept and notify"})
@pytest.mark.parametrize("dialog_type, retval", [
    ("alert", None),
    ("beforeunload", None),
    ("confirm", True),
    ("prompt", ""),
])
def test_accept_and_notify(
    check_beforeunload_implicitly_accepted,
    check_user_prompt_closed_with_exception,
    dialog_type,
    retval
):
    if dialog_type == "beforeunload":
        check_beforeunload_implicitly_accepted()
    else:
        check_user_prompt_closed_with_exception(dialog_type, retval)


@pytest.mark.capabilities({"unhandledPromptBehavior": "dismiss"})
@pytest.mark.parametrize("dialog_type", ["alert", "beforeunload", "confirm", "prompt"])
def test_dismiss(
    check_beforeunload_implicitly_accepted,
    check_user_prompt_closed_without_exception,
    dialog_type
):
    if dialog_type == "beforeunload":
        check_beforeunload_implicitly_accepted()
    else:
        # retval not testable for confirm and prompt because window is gone
        check_user_prompt_closed_without_exception(dialog_type, None)


@pytest.mark.capabilities({"unhandledPromptBehavior": "dismiss and notify"})
@pytest.mark.parametrize("dialog_type, retval", [
    ("alert", None),
    ("beforeunload", None),
    ("confirm", False),
    ("prompt", None),
])
def test_dismiss_and_notify(
    check_beforeunload_implicitly_accepted,
    check_user_prompt_closed_with_exception, dialog_type,
    retval
):
    if dialog_type == "beforeunload":
        check_beforeunload_implicitly_accepted()
    else:
        check_user_prompt_closed_with_exception(dialog_type, retval)


@pytest.mark.capabilities({"unhandledPromptBehavior": "ignore"})
@pytest.mark.parametrize("dialog_type", ["alert", "beforeunload", "confirm", "prompt"])
def test_ignore(
    check_beforeunload_implicitly_accepted,
    check_user_prompt_not_closed_but_exception,
    dialog_type
):
    if dialog_type == "beforeunload":
        check_beforeunload_implicitly_accepted()
    else:
        check_user_prompt_not_closed_but_exception(dialog_type)


@pytest.mark.parametrize("dialog_type, retval", [
    ("alert", None),
    ("beforeunload", None),
    ("confirm", False),
    ("prompt", None),
])
def test_default(
    check_beforeunload_implicitly_accepted,
    check_user_prompt_closed_with_exception,
    dialog_type,
    retval
):
    if dialog_type == "beforeunload":
        check_beforeunload_implicitly_accepted()
    else:
        check_user_prompt_closed_with_exception(dialog_type, retval)
