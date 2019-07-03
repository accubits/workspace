import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { RenewPopupComponent } from './renew-popup.component';

describe('RenewPopupComponent', () => {
  let component: RenewPopupComponent;
  let fixture: ComponentFixture<RenewPopupComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ RenewPopupComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(RenewPopupComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
