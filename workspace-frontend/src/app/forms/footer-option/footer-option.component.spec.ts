import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FooterOptionComponent } from './footer-option.component';

describe('FooterOptionComponent', () => {
  let component: FooterOptionComponent;
  let fixture: ComponentFixture<FooterOptionComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FooterOptionComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FooterOptionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
