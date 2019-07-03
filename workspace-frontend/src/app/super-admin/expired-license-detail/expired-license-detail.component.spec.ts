import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ExpiredLicenseDetailComponent } from './expired-license-detail.component';

describe('ExpiredLicenseDetailComponent', () => {
  let component: ExpiredLicenseDetailComponent;
  let fixture: ComponentFixture<ExpiredLicenseDetailComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ExpiredLicenseDetailComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ExpiredLicenseDetailComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
