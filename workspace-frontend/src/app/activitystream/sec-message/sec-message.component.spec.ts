import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SecMessageComponent } from './sec-message.component';

describe('SecMessageComponent', () => {
  let component: SecMessageComponent;
  let fixture: ComponentFixture<SecMessageComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SecMessageComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SecMessageComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
