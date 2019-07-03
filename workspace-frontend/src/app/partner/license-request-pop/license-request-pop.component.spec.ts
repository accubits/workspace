import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { LicenseRequestPopComponent } from './license-request-pop.component';

describe('LicenseRequestPopComponent', () => {
  let component: LicenseRequestPopComponent;
  let fixture: ComponentFixture<LicenseRequestPopComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ LicenseRequestPopComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(LicenseRequestPopComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
