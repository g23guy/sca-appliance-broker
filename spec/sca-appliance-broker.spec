# Copyright (C) 2013 SUSE LLC
# This file and all modifications and additions to the pristine
# package are under the same license as the package itself.
#
# norootforbuild
# neededforbuild
%define sca_common sca

Name:         sca-appliance-broker
Summary:      Supportconfig Analysis Appliance Broker
Group:        Documentation/SuSE
Distribution: SUSE Linux Enterprise
Vendor:       SUSE Support
License:      GPLv2
Autoreqprov:  on
Version:      1.2
Release:      1.140102.PTF.1
Source:       %{name}-%{version}.tar.gz
BuildRoot:    %{_tmppath}/%{name}-%{version}
Buildarch:    noarch
Requires:     apache2
Requires:     /usr/bin/dos2unix
Requires:     /usr/sbin/mysqld
Requires:     sca-appliance-common

%description
Monitors inbound supportconfig archives and is responsible for
assigning new and retry archives states for appropriate agent analysis. 

Authors:
--------
    Jason Record <jrecord@suse.com>

%prep
%setup -q

%build
gzip -9f man/*

%install
pwd;ls -la
rm -rf $RPM_BUILD_ROOT
install -d $RPM_BUILD_ROOT/etc/opt/%{sca_common}
install -d $RPM_BUILD_ROOT/opt/%{sca_common}/bin
install -d $RPM_BUILD_ROOT/srv/www/htdocs/sca
install -d $RPM_BUILD_ROOT/usr/sbin
install -d $RPM_BUILD_ROOT/usr/share/man/man1
install -d $RPM_BUILD_ROOT/usr/share/man/man5
install -d $RPM_BUILD_ROOT/usr/share/doc/packages/%{sca_common}
install -d $RPM_BUILD_ROOT/var/opt/%{sca_common}
install -m 644 config/*.conf $RPM_BUILD_ROOT/etc/opt/%{sca_common}
install -m 644 config/* $RPM_BUILD_ROOT/usr/share/doc/packages/%{sca_common}
install -m 544 bin/* $RPM_BUILD_ROOT/opt/%{sca_common}/bin
install -m 644 websca/index.html $RPM_BUILD_ROOT/usr/share/doc/packages/%{sca_common}
install -m 644 websca/* $RPM_BUILD_ROOT/srv/www/htdocs/sca
install -m 544 bin/scadb $RPM_BUILD_ROOT/usr/sbin
install -m 544 bin/setup-sca $RPM_BUILD_ROOT/usr/sbin
install -m 644 schema/* $RPM_BUILD_ROOT/usr/share/doc/packages/%{sca_common}
install -m 644 docs/* $RPM_BUILD_ROOT/usr/share/doc/packages/%{sca_common}
install -m 644 man/*.1.gz $RPM_BUILD_ROOT/usr/share/man/man1
install -m 644 man/*.5.gz $RPM_BUILD_ROOT/usr/share/man/man5

%files
%defattr(-,root,root)
%dir /opt
%dir /etc/opt
%dir /var/opt
%dir /srv/www/htdocs/sca
%dir /opt/%{sca_common}/bin
%dir /opt/%{sca_common}
%dir /etc/opt/%{sca_common}
%dir /var/opt/%{sca_common}
%dir /usr/share/doc/packages/%{sca_common}
/usr/sbin/setup-sca
/usr/sbin/scadb
/opt/%{sca_common}/bin/*
%config /etc/opt/%{sca_common}/sdbroker.conf
%doc /usr/share/man/man1/*
%doc /usr/share/man/man5/*
%attr(-,wwwrun,www) /srv/www/htdocs/sca
%attr(-,wwwrun,www) /usr/share/doc/packages/%{sca_common}/index.html
%doc /usr/share/doc/packages/%{sca_common}/*

%post
if [ -s /srv/www/htdocs/index.html ]; then
	if grep -i '<html><body><h1>It works!</h1></body></html>' /srv/www/htdocs/index.html &>/dev/null; then
		cp -a /usr/share/doc/packages/%{sca_common}/index.html /srv/www/htdocs/
	else
		echo
		echo "WARNING: File already exists: /srv/www/htdocs/index.html"
		echo " Redirector /usr/share/doc/packages/%{sca_common}/index.html will not be installed."
		echo
	fi
else
	cp -a /usr/share/doc/packages/%{sca_common}/index.html /srv/www/htdocs/
fi

%changelog
* Thu Jan 02 2014 jrecord@suse.com
- separated sca-appliance-common files

* Mon Dec 23 2013 jrecord@suse.com
- setup-sca package names changes
- fixed blank hypervisor lines
- sdbroker no longer inserts FileLocation

* Thu Dec 20 2013 jrecord@suse.com
- separated as individual RPM package
- SCA reports page sorts by all columns
- fixed SCA XSS vulnerabilities
- fixed SCA SQL injection vulnerabilities

