import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CreateOrgLicenseComponent } from './create-org-license.component';

describe('CreateOrgLicenseComponent', () => {
  let component: CreateOrgLicenseComponent;
  let fixture: ComponentFixture<CreateOrgLicenseComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CreateOrgLicenseComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CreateOrgLicenseComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
