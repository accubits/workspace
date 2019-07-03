import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PermissionsPopupComponent } from './permissions-popup.component';

describe('PermissionsPopupComponent', () => {
  let component: PermissionsPopupComponent;
  let fixture: ComponentFixture<PermissionsPopupComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PermissionsPopupComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PermissionsPopupComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
