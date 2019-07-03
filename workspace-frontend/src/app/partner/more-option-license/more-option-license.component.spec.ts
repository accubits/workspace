import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { MoreOptionLicenseComponent } from './more-option-license.component';

describe('MoreOptionLicenseComponent', () => {
  let component: MoreOptionLicenseComponent;
  let fixture: ComponentFixture<MoreOptionLicenseComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ MoreOptionLicenseComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(MoreOptionLicenseComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
