import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PopupOverlayComponent } from './popup-overlay.component';

describe('PopupOverlayComponent', () => {
  let component: PopupOverlayComponent;
  let fixture: ComponentFixture<PopupOverlayComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PopupOverlayComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PopupOverlayComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
