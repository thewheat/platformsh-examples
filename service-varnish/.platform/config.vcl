acl purge {
    "localhost";
    "192.0.2.0"/24;
    "13.55.135.0"/32;
    "13.54.121.225"/32;
    "13.55.215.151"/32;
}

sub vcl_recv {
    set req.backend_hint = application.backend();

    # https://docs.platform.sh/add-services/varnish.html#clear-cache-with-a-push
    if (req.method == "PURGE") {
        # The Platform.sh router will provide the real client IP as X-Client-IP
        # Use std.ip to convert the string to an IP for comparison
        if (!std.ip(req.http.X-Client-IP) ~ purge) {
            # Deny all purge requests not from the allowed IPs
            return(synth(403,"Not allowed."));
        }
        # Purge cache for allowed requests
        return (purge);
    }

}